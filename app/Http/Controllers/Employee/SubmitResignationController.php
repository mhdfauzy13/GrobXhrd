<?php

namespace App\Http\Controllers\Employee;

use App\Models\ResignationRequest;
use App\Models\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubmitResignationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:resignationrequest.create', ['only' => ['store']]);
        $this->middleware('permission:resignationrequest.index', ['only' => ['index']]);
        $this->middleware('permission:submitresign.index', ['only' => ['index']]);
        $this->middleware('permission:submitresign.create', ['only' => ['create']]);
        $this->middleware('role:manager|superadmin');
    }

    // Menampilkan daftar data pengunduran diri yang telah disubmit
    public function index()
    {
        $resignationRequests = ResignationRequest::with('employee', 'manager')
            ->where('status', 'approved')
            ->get();

        return view('employee.submitresign.index', compact('resignationRequests'));
    }

    // form pengajuan pengunduran diri
    public function create()
    {
        $employees = Employee::all();

        return view('employee.submitresign.create', compact('employees'));
    }

    // Menyimpan pengajuan pengunduran diri
    public function store(Request $request)
    {
        // Validasi Input tanpa status
        $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'resign_date' => 'required|date|after_or_equal:' . now()->toDateString(),
            'reason' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        // Ambil data employee berdasarkan employee_id yang dipilih
        $employee = Employee::where('employee_id', $request->employee_id)->first();

        if (!$employee) {
            return redirect()->back()->withErrors(['employee_id' => 'Employee not found']);
        }

        try {
            ResignationRequest::create([
                'employee_id' => $request->employee_id,
                'name' => $employee->first_name . ' ' . $employee->last_name,
                'resign_date' => $request->resign_date,
                'reason' => $request->reason,
                'remarks' => $request->remarks,
                'status' => 'approved',  // Status langsung di-set menjadi 'approved'
            ]);

            return redirect()->route('submitresign.index')->with('success', 'Resignation submitted and approved automatically!');
        } catch (\Exception $e) {
            Log::error('Error saving resignation: ', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'There was an error while submitting your resignation. Please try again later.']);
        }
    }
}
