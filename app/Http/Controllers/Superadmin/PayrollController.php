<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payroll.index')->only('index');
        $this->middleware('permission:payroll.create')->only(['create', 'store']);
        $this->middleware('permission:payroll.edit')->only(['edit', 'update']);
        $this->middleware('permission:payroll.delete')->only('destroy');
    }


    public function index()
    {
        $payrolls = Payroll::with('employee')->get();
        return view('Superadmin.payroll.index', compact('payrolls'));
    }

    public function create()
    {
        $employees = Employee::all(); // Ambil data karyawan dari tabel employees
        return view('Superadmin.payroll.create', compact('employees'));
    }

    // Menyimpan payroll baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'employee_id' => 'required',
            'allowance' => 'nullable|numeric',
            'overtime' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
        ]);

        // Ambil current_salary dari employee
        $employee = Employee::findOrFail($request->employee_id);
        $basic_salary = $employee->current_salary;

        // Set nilai default untuk tunjangan, lembur, dan potongan jika tidak diisi
        $allowance = $request->allowance ?? 0;
        $overtime = $request->overtime ?? 0;
        $deductions = $request->deductions ?? 0;

        // Hitung total gaji
        $total_salary = $basic_salary + $allowance + $overtime - $deductions;

        // Simpan payroll ke database
        Payroll::create([
            'employee_id' => $request->employee_id,
            'allowance' => $allowance,
            'overtime' => $overtime,
            'deductions' => $deductions,
            'total_salary' => $total_salary,
        ]);

        return redirect()->route('payroll.index')->with('success', 'Payroll berhasil ditambahkan');
    }

    public function edit($id)
    {
        $payroll = Payroll::with('employee')->findOrFail($id); // Ambil data payroll berdasarkan ID
        $employees = Employee::all(); // Ambil data semua karyawan untuk dropdown
        return view('payroll.edit', compact('payroll', 'employees'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'allowance' => 'nullable|numeric',
            'overtime' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
        ]);

        // Ambil payroll yang ingin diperbarui
        $payroll = Payroll::findOrFail($id);

        // Ambil current_salary dari employee
        $employee = Employee::findOrFail($request->employee_id);
        $basic_salary = $employee->current_salary;

        // Set nilai default untuk tunjangan, lembur, dan potongan jika tidak diisi
        $allowance = $request->allowance ?? 0;
        $overtime = $request->overtime ?? 0;
        $deductions = $request->deductions ?? 0;

        // Hitung total gaji
        $total_salary = $basic_salary + $allowance + $overtime - $deductions;

        // Update data payroll
        $payroll->update([
            'employee_id' => $request->employee_id,
            'allowance' => $allowance,
            'overtime' => $overtime,
            'deductions' => $deductions,
            'total_salary' => $total_salary,
        ]);

        return redirect()->route('payroll.index')->with('success', 'Payroll berhasil diperbarui');
    }

    public function destroy($id)
    {
        // Ambil payroll berdasarkan ID
        $payroll = Payroll::findOrFail($id);
        $payroll->delete(); // Hapus payroll

        return redirect()->route('payroll.index')->with('success', 'Payroll berhasil dihapus');
    }
}
