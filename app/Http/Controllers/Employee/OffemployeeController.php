<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Mail\OffRequestStatusMail;
use App\Models\Offrequest;
use App\Models\User;
use App\Notifications\OffRequestEmailNotification;
use App\Notifications\OffRequestNotification;
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
        $this->middleware('permission:offrequest.create')->only(['create', 'store']);
        $this->middleware('permission:offrequest.approver')->only(['approverIndex', 'approve', 'reject']);
    }

    // Fungsi untuk menampilkan daftar off request milik user yang sedang login (sisi karyawan)
    public function index()
    {
        // Menghitung total hari cuti berdasarkan title yang tidak di-reject
        $totals = Offrequest::select('title', DB::raw('SUM(DATEDIFF(end_event, start_event) + 1) as total_days'))->where('status', 'approved')->groupBy('title')->get();

        $offrequests = Offrequest::with(['employee', 'manager'])->paginate(10);

        return view('employee.offrequest.index', compact('offrequests', 'totals'));
    }

    public function create()
    {
        $approvers = User::permission('offrequest.approver')->get();
        return view('employee.offrequest.create', compact('approvers'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'title' => 'required|in:Sakit,Liburan,Urusan Keluarga,Absence,Personal Time',
            'description' => 'required|string',
            'start_event' => 'required|date|after_or_equal:today',
            'end_event' => 'required|date|after_or_equal:start_event',
            'manager_id' => 'nullable|exists:employees,employee_id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
        ]);

        $employee = Auth::user(); // Mendapatkan data employee yang sedang login

        // Cek apakah ada cuti yang sudah disetujui atau pending pada tanggal yang sama
        $existingRequest = Offrequest::where('employee_id', $employee->employee_id) // Menggunakan 'employee_id' untuk merujuk ke employee yang sedang login
            ->where(function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('start_event', '<=', $request->end_event)->where('end_event', '>=', $request->start_event);
                });
            })
            ->whereIn('status', ['approved', 'pending'])
            ->first();

        // Jika ada pengajuan yang tumpang tindih, tampilkan pesan error spesifik
        if ($existingRequest) {
            if ($existingRequest->status === 'pending') {
                return redirect()->route('offrequest.index')->with('error', 'Anda sudah memiliki pengajuan cuti yang sedang diproses pada tanggal ini. Silakan tunggu hingga disetujui atau ditolak.');
            }

            if ($existingRequest->status === 'approved') {
                return redirect()->route('offrequest.index')->with('error', 'Anda sudah memiliki pengajuan cuti yang disetujui pada tanggal ini.');
            }
        }

        // Upload file gambar jika ada
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads'), $imageName);
        }

        // Simpan data ke database
        $offrequest = new Offrequest();
        $offrequest->employee_id = $employee->employee_id; // Menggunakan employee_id
        $offrequest->name = $employee->name;
        $offrequest->email = $employee->email;
        $offrequest->manager_id = $request->manager_id;
        $offrequest->title = $request->title;
        $offrequest->description = $request->description;
        $offrequest->start_event = $request->start_event;
        $offrequest->end_event = $request->end_event;
        $offrequest->status = 'pending';
        $offrequest->image = $imageName; 
        $offrequest->save();

        // Kirim notifikasi email ke manager
        $managers = User::role('manager')->get(); // Ambil semua user dengan role 'manager'
        foreach ($managers as $manager) {
            $manager->notify(new OffRequestEmailNotification($offrequest)); // Kirim notifikasi email ke setiap manager
        }

        return redirect()->route('offrequest.index')->with('success', 'Off request submitted successfully.');
    }

    // Fungsi untuk menampilkan daftar off request untuk approver (sisi manager)
    public function approverIndex()
    {
        // Dapatkan ID dari approver (manager) yang sedang login
        $approverId = Auth::id();

        // Ambil daftar offrequest di mana manager_id adalah approver yang sedang login
        $offrequests = Offrequest::pending()->forManager($approverId)->get();

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
        $offrequest->approver_id = auth()->user()->employee_id;
        $offrequest->save();

        // Mengirim notifikasi email ke pengguna
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
        $offrequest->approver_id = auth()->user()->employee_id;
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
