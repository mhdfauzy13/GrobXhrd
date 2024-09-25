<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Offrequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class OffemployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:offrequest.index')->only(['index']);
        $this->middleware('permission:offrequest.create')->only(['create']);
        $this->middleware('permission:offrequest.store')->only(['store']);
        $this->middleware('permission:offrequest.approver')->only(['approverIndex','approve', 'reject']);
    }

    // Fungsi untuk menampilkan daftar off request milik user yang sedang login (sisi karyawan)
    public function index()
    {
        // Ambil offrequest milik user yang sedang login
        $offrequests = Offrequest::where('user_id', Auth::id())->get();

        return view('employee.offrequest.index', compact('offrequests'));
    }

    public function create()
    {
        $approvers = User::permission('offrequest.approve')->get();
        return view('employee.offrequest.create', compact('approvers'));
    }


    public function store(Request $request)
    {


        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_event' => 'required|date',
            'end_event' => 'required|date',
            'manager_id' => 'nullable|exists:users,user_id', // Validasi manager_id
        ]);

        $user = Auth::user(); // Ambil user yang sedang login

        // Cek apakah user sudah memiliki pengajuan cuti dengan status pending
        $existingRequest = Offrequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return redirect()->route('offrequest.index')
                ->with('error', 'You already have a pending leave request. Please wait until it is approved.');
        }

      

        $offrequest = new Offrequest();
        $offrequest->user_id = $user->user_id; // Ambil user_id dari pengguna yang login
        $offrequest->name = $user->name; // Ambil nama dari pengguna yang login
        $offrequest->email = $user->email; // Ambil email dari pengguna yang login
        $offrequest->manager_id = $request->manager_id; // ID manager dari dropdown
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
        // Ambil daftar offrequest di mana manager_id adalah approver yang sedang login
        $offrequests = Offrequest::where('status', 'pending')
            ->get();

        return view('employee.offrequest.approve', compact('offrequests'));
    }


    // Fungsi untuk approve permohonan cuti
    public function approve($id)
    {
        // Cari offrequest berdasarkan id
        $offrequest = Offrequest::findOrFail($id);

        // Update status menjadi 'approved'
        $offrequest->status = 'approved';
        $offrequest->save();

        return redirect()->route('offrequest.approver')->with('success', 'Off request approved successfully.');
    }

    // Fungsi untuk reject permohonan cuti
    public function reject($id)
    {
        // Cari offrequest berdasarkan id
        $offrequest = Offrequest::findOrFail($id);

        // Update status menjadi 'rejected'
        $offrequest->status = 'rejected';
        $offrequest->save();

        return redirect()->route('offrequest.approver')->with('success', 'Off request rejected successfully.');
    }
}
