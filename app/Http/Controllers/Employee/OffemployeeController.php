<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Mail\OffRequestStatusMail;
use App\Models\Offrequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class OffemployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:offrequest.index')->only(['index']);
        $this->middleware('permission:offrequest.create')->only(['create']);
        $this->middleware('permission:offrequest.store')->only(['store']);
        $this->middleware('permission:offrequest.approver')->only(['approverIndex', 'approve', 'reject']);
    }

    // Fungsi untuk menampilkan daftar off request milik user yang sedang login (sisi karyawan)
    public function index()
    {
        // Menghitung total hari cuti berdasarkan title yang tidak di-reject
        $totals = Offrequest::select('title', DB::raw('SUM(DATEDIFF(end_event, start_event) + 1) as total_days'))
            ->where('status', 'approved')
            ->groupBy('title')
            ->get();

        $offrequests = Offrequest::with(['user', 'manager'])->paginate(10);



        return view('employee.offrequest.index', compact('offrequests', 'totals'));
    }

    public function create()
    {
        $approvers = User::permission('offrequest.approver')->get();
        return view('employee.offrequest.create', compact('approvers'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|in:Sakit,Liburan,Urusan Keluarga,Absence,Personal Time',
            'description' => 'required|string',
            'start_event' => 'required|date|after_or_equal:today',
            'end_event' => 'required|date|after_or_equal:start_event',
            'manager_id' => 'nullable|exists:users,user_id',
        ]);

        $user = Auth::user();

        // Cek apakah ada cuti yang sudah disetujui atau pending pada tanggal yang sama
        $existingRequest = Offrequest::where('user_id', $user->id)
            ->where(function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('start_event', '<=', $request->end_event)
                        ->where('end_event', '>=', $request->start_event);
                });
            })
            ->whereIn('status', ['approved', 'pending'])
            ->first();

        // Jika ada pengajuan yang tumpang tindih, tampilkan pesan error spesifik
        if ($existingRequest) {
            if ($existingRequest->status === 'pending') {
                return redirect()->route('offrequest.index')
                    ->with('error', 'You already have a pending leave request on this date. Please wait for approval or rejection.');
            }

            if ($existingRequest->status === 'approved') {
                return redirect()->route('offrequest.index')
                    ->with('error', 'You already have an approved leave request on this date.');
            }
        }

        $offrequest = new Offrequest();
        $offrequest->user_id = $user->user_id;
        $offrequest->name = $user->name;
        $offrequest->email = $user->email;
        $offrequest->manager_id = $request->manager_id;
        $offrequest->title = $request->title;
        $offrequest->description = $request->description;
        $offrequest->start_event = $request->start_event;
        $offrequest->end_event = $request->end_event;
        $offrequest->status = 'pending';
        $offrequest->save();

        return redirect()->route('offrequest.index')->with('success', 'Off request submitted successfully.');
    }

    // Fungsi untuk menampilkan daftar off request untuk approver (sisi manager)
    public function approverIndex()
    {
        // Dapatkan ID dari approver (manager) yang sedang login
        $approverId = Auth::id();

        // Ambil daftar offrequest di mana manager_id adalah approver yang sedang login
        $offrequests = Offrequest::pending()
            ->forManager($approverId)
            ->get();

        $approvedRequests = Offrequest::where('manager_id', $approverId)
            ->whereIn('status', ['approved', 'rejected'])
            ->get();

        return view('employee.offrequest.approve', compact('offrequests', 'approvedRequests'));
    }

    // Fungsi untuk approve permohonan cuti
    public function approve($id)
    {
        // Cari offrequest berdasarkan id
        $offrequest = Offrequest::findOrFail($id);

        // Update status menjadi 'approved'
        $offrequest->status = 'approved';
        $offrequest->approver_id = auth()->user()->user_id;
        $offrequest->save();


        Mail::to($offrequest->user->email)->send(new OffrequestStatusMail($offrequest, 'approved'));


        return redirect()->route('offrequest.approver')->with('success', 'Off request approved successfully.');
    }

    // Fungsi untuk reject permohonan cuti
    public function reject($id)
    {
        // Cari offrequest berdasarkan id
        $offrequest = Offrequest::findOrFail($id);

        // Update status menjadi 'rejected'
        $offrequest->status = 'rejected';
        $offrequest->approver_id = auth()->user()->user_id;
        $offrequest->save();

        Mail::to($offrequest->user->email)->send(new OffrequestStatusMail($offrequest, 'rejected'));

        return redirect()->route('offrequest.approver')->with('success', 'Off request rejected successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $offRequest = OffRequest::find($id);
        $status = $request->input('status'); // status 'approved' atau 'rejected'

        $offRequest->status = $status;
        $offRequest->save();

        // Kirim email notifikasi
        Mail::to($offRequest->email)->send(new OffRequestStatusMail($offRequest, $status));

        return redirect()->back()->with('success', 'Status pengajuan cuti berhasil diubah dan email notifikasi telah dikirim.');
    }
}
