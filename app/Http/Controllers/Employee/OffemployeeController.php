<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Mail\OffRequestStatusMail;
use App\Models\Offrequest;
use App\Models\User;
use App\Notifications\OffRequestEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OffemployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:offrequest.index')->only(['index']);
        $this->middleware('permission:offrequest.create')->only(['create', 'store']);
        $this->middleware('permission:offrequest.approver')->only(['approverIndex', 'approve', 'reject']);
    }

    public function index()
    {
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
        // Validasi input
        $request->validate([
            'title' => 'required|in:Sick,Holiday,Family Matters,Absence,Personal Time',
            'description' => 'required|string',
            'start_event' => 'required|date|after_or_equal:today',
            'end_event' => 'required|date|after_or_equal:start_event',
            'manager_id' => 'nullable|exists:users,user_id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Cek apakah ada cuti yang tumpang tindih
        $existingRequest = Offrequest::where('user_id', $user->user_id)
            ->where(function ($query) use ($request) {
                $query->where('start_event', '<=', $request->end_event)
                    ->where('end_event', '>=', $request->start_event);
            })
            ->whereIn('status', ['approved', 'pending'])
            ->first();

        if ($existingRequest) {
            $message = $existingRequest->status === 'pending'
                ? 'Anda sudah memiliki pengajuan cuti yang sedang diproses.'
                : 'Anda sudah memiliki pengajuan cuti yang disetujui.';
            return redirect()->route('offrequest.index')->with('error', $message);
        }

        // Upload file gambar jika ada
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = $this->uploadImage($request->file('image')); // Menangani upload gambar
        }

        // Simpan data ke database
        $offrequest = Offrequest::create([
            'user_id' => $user->user_id,
            'name' => $user->name,
            'email' => $user->email,
            'manager_id' => $request->manager_id,
            'title' => $request->title,
            'description' => $request->description,
            'start_event' => $request->start_event,
            'end_event' => $request->end_event,
            'status' => 'pending',
            'image' => $imageName,  // Menyimpan nama file gambar
        ]);

        // Kirim notifikasi ke manager
        $managers = User::role('manager')->get();
        foreach ($managers as $manager) {
            $manager->notify(new OffRequestEmailNotification($offrequest));
        }

        return redirect()->route('offrequest.index')->with('success', 'Pengajuan cuti berhasil diajukan.');
    }

    // Fungsi untuk menangani upload gambar
    // public function uploadImage($image)
    // {
    //     // Membuat nama file unik berdasarkan waktu
    //     $imageName = time() . '.' . $image->getClientOriginalExtension();

    //     // Menyimpan gambar ke folder public/uploads
    //     $image->move(public_path('uploads'), $imageName);

    //     return $imageName;
    // }

    public function uploadImage(Request $request, $offrequestId)
    {
        // Validasi file gambar
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Cari permohonan cuti berdasarkan ID
        $offrequest = Offrequest::findOrFail($offrequestId);

        // Proses upload gambar
        if ($request->hasFile('image')) {
            // Upload gambar dan simpan di folder uploads
            $imagePath = $request->file('image')->store('uploads', 'public');
            
            // Simpan path gambar di kolom image permohonan cuti
            $offrequest->image = $imagePath;
            $offrequest->save();

            // Beri feedback sukses
            return redirect()->route('offrequest.index')->with('success', 'Proof of leave uploaded successfully!');
        }

        // Jika tidak ada file yang diupload
        return redirect()->route('offrequest.index')->with('error', 'No image uploaded.');
    }

    public function approverIndex()
    {
        $approverId = Auth::id();

        $offrequests = Offrequest::pending()->forManager($approverId)->get();

        $approvedRequests = Offrequest::where('manager_id', $approverId)
            ->whereIn('status', ['approved', 'rejected'])
            ->get();

        return view('employee.offrequest.approve', compact('offrequests', 'approvedRequests'));
    }

    public function approve($id)
    {
        $offrequest = Offrequest::findOrFail($id);
        $offrequest->update([
            'status' => 'approved',
            'approver_id' => auth()->user()->user_id,
        ]);

        Mail::to($offrequest->user->email)->send(new OffRequestStatusMail($offrequest, 'approved'));

        return redirect()->route('offrequest.approver')->with('success', 'Pengajuan cuti berhasil disetujui.');
    }

    public function reject($id)
    {
        $offrequest = Offrequest::findOrFail($id);
        $offrequest->update([
            'status' => 'rejected',
            'approver_id' => auth()->user()->user_id,
        ]);

        Mail::to($offrequest->user->email)->send(new OffRequestStatusMail($offrequest, 'rejected'));

        return redirect()->route('offrequest.approver')->with('success', 'Pengajuan cuti berhasil ditolak.');
    }
}
