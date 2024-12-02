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
        $this->middleware('permission:overtime.approvals')->only(['approvals', 'updateStatus']);
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
        $request->validate([
            'overtime_date' => 'required|date',
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
    $managerId = auth()->user()->id;

    $pendingOvertimes = Overtime::where('status', 'pending')
                                ->where('manager_id', $managerId)
                                ->get();

    // Debug untuk memastikan hanya lembur dengan manager_id sesuai yang diambil
    // dd($pendingOvertimes);

    return view('superadmin.overtime.approve', compact('pendingOvertimes'));
}

    
    
    
        // Metode untuk update status overtime
        public function updateStatus($id, Request $request)
        {
            $overtime = Overtime::findOrFail($id); // Temukan data lembur berdasarkan ID
        
            // Pastikan hanya manager yang dituju dapat mengakses
            if ($overtime->manager_id != auth()->user()->id) {
                return redirect()->back()->with('error', 'You are not authorized to approve this overtime.');
            }
        
            // Validasi input status
            $request->validate([
                'status' => 'required|in:approved,rejected',
            ]);
        
            // Update status lembur
            $overtime->status = $request->status;
            $overtime->save();
        
            return redirect()->route('overtime.approvals')->with('success', 'Overtime status updated successfully.');
        }
        
        
        
    
    
}
