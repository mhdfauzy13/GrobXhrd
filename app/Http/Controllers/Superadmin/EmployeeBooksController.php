<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\Employee;
use App\Models\EmployeeBook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmployeeBooksController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:employeebook.index')->only('index');
        $this->middleware('permission:employeebook.create')->only(['create', 'store']);
        $this->middleware('permission:employeebook.edit')->only(['edit', 'update']);
        $this->middleware('permission:employeebook.delete')->only('destroy');
    }

    public function index()
    {
        $violations = EmployeeBook::where('category', 'violation')->with('employee')->get();
        $warnings = EmployeeBook::where('category', 'warning')->with('employee')->get();
        $reprimands = EmployeeBook::where('category', 'reprimand')->with('employee')->get();
        $employees = Employee::all(); // Ambil semua karyawan

        return view('superadmin.employeebooks.index', compact('violations', 'warnings', 'reprimands', 'employees'));
    }

    public function create(Request $request)
    {
        $employees = Employee::all(); // Ambil semua karyawan
        $category = $request->query('category', 'violation'); // Set kategori default
        return view('superadmin.employeebooks.create', compact('employees', 'category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'incident_date' => 'required|date',
            'incident_detail' => 'required|string',
            'remarks' => 'required|string',
            'category' => 'required|string',
        ]);

        // Simpan data ke database
        EmployeeBook::create($request->all());

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('employeebooks.index')->with('success', 'Employee book created successfully.');
    }
}
