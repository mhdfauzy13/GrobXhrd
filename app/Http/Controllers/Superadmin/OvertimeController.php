<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OvertimeController extends Controller
{
    public function __construct()
    {
        // Menggunakan middleware untuk validasi hak akses
        $this->middleware('permission:overtime.create')->only(['index', 'create', 'store', 'searchEmployees']);
        $this->middleware('permission:overtime.approvals')->only(['approvals', 'updateStatus','approve']);
    }

    public function index()
    {
        // Mengambil data overtime beserta relasi user dan employee
        $overtimes = Overtime::where('user_id', Auth::id())
            ->with('user.employee') // Memuat relasi user dan employee
            ->get();

        return view('superadmin.overtime.index', compact('overtimes'));
    }

    public function create()
    {
        // Ambil daftar manager (user dengan role 'manager')
        $managers = User::role('manager')->get();

        return view('superadmin.overtime.create', compact('managers'));
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'overtime_date' => 'required|date',
        //     'duration' => 'required|integer|min:1',
        //     'notes' => 'required|string|max:255',
        //     'manager_id' => 'required|exists:users,user_id',
        // ]);

        $request->validate([
            'overtime_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $existingOvertime = Overtime::where('user_id', Auth::id())
                                                ->where('overtime_date', $value)
                                                ->whereIn('status', ['pending', 'approved'])
                                                ->exists();
                    if ($existingOvertime) {
                        $fail('Anda sudah memiliki pengajuan overtime untuk tanggal ini.');
                    }
                }
            ],
            'duration' => 'required|integer|min:1',
            'notes' => 'required|string|max:255',
            'manager_id' => 'required|exists:users,user_id',
        ]);
        

        Overtime::create([
            'user_id' => Auth::id(),
            'overtime_date' => $request->overtime_date,
            'duration' => $request->duration,
            'notes' => $request->notes,
            'manager_id' => $request->manager_id,
            'status' => 'pending',
        ]);

        return redirect()->route('overtime.index')->with('success');
    }

    public function approvals()
    {
        // Pengajuan overtime yang masih menunggu persetujuan
        $pendingOvertimes = Overtime::where('status', 'pending')->get();

        // Riwayat overtime yang sudah disetujui/rejected oleh manager yang sedang login
        $managerId = auth()->user()->id; // Mendapatkan ID manager yang sedang login
        $historyOvertimes = Overtime::whereIn('status', ['approved', 'rejected'])
            ->where('manager_id', $managerId) // Filter berdasarkan manager yang sedang login
            ->get();

        return view('superadmin.overtime.approve', compact('pendingOvertimes', 'historyOvertimes'));
    }

    // Metode untuk update status overtime
    public function updateStatus($id, Request $request)
    {
        // Ambil status dari form
        $status = $request->input('status');

        // Pastikan status yang diterima valid
        if (!in_array($status, ['approved', 'rejected'])) {
            return redirect()->back()->with('error', 'Invalid status.');
        }

        // Temukan overtime berdasarkan ID
        $overtime = Overtime::findOrFail($id);

        // Update status overtime
        $overtime->status = $status;
        $overtime->save(); // Simpan perubahan ke database

        // Kembalikan response dengan pesan sukses
        return redirect()->route('overtime.index')->with('success', 'Overtime status updated successfully.');
    }
}
