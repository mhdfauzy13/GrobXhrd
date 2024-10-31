<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Attandance;
use App\Models\AttandanceRecap;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payroll.index')->only('index');
        $this->middleware('permission:payroll.create')->only(['create', 'store', 'updateValidationStatus']);
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

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $payroll = $this->calculatePayroll($request->employee_id);

        return redirect()->route('payroll.index')->with('success', 'Payroll berhasil dibuat');
    }


    public function calculatePayroll($employeeId)
    {
        // Ambil data karyawan, termasuk current_salary dari model Employee
        $employee = Employee::findOrFail($employeeId);
        $currentSalary = $employee->current_salary;

        // Ambil semua catatan attendance untuk karyawan ini
        $attendanceRecords = Attandance::where('employee_id', $employeeId)->get();

        // Hitung total kehadiran, cuti, dan hari kerja efektif
        $daysPresent = $attendanceRecords->where('check_in_status', 'present')->count();
        $totalLeave = $attendanceRecords->where('check_in_status', 'leave')->count();
        $effectiveWorkDays = $daysPresent - $totalLeave;

        // Hitung total gaji berdasarkan hari kerja efektif
        $totalSalary = $effectiveWorkDays * ($currentSalary / 30); // Gaji harian dihitung dari gaji bulanan

        // Simpan data payroll
        return Payroll::create([
            'employee_id' => $employeeId,
            'employee_name' => $employee->name,
            'days_present' => $daysPresent,
            'total_leave' => $totalLeave,
            'effective_work_days' => $effectiveWorkDays,
            'current_salary' => $currentSalary,
            'total_salary' => $totalSalary,
            'validation_status' => 'not_validated'
        ]);
    }

    public function updateStatus(Request $request, $payrollId)
    {
        $request->validate([
            'validation_status' => 'required|in:validated,not_validated'
        ]);

        $payroll = Payroll::findOrFail($payrollId);
        $payroll->update([
            'validation_status' => $request->validation_status,
        ]);

        return redirect()->back()->with('success', 'Status validasi berhasil diperbarui');
    }




    // public function edit($id)
    // {
    //     $payroll = Payroll::with('employee')->findOrFail($id); // Ambil data payroll berdasarkan ID
    //     $employees = Employee::all(); // Ambil data semua karyawan untuk dropdown
    //     return view('payroll.edit', compact('payroll', 'employees'));
    // }

    // public function update(Request $request, $id)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'employee_id' => 'required|exists:employees,id',
    //         'allowance' => 'nullable|numeric',
    //         'overtime' => 'nullable|numeric',
    //         'deductions' => 'nullable|numeric',
    //     ]);

    //     // Ambil payroll yang ingin diperbarui
    //     $payroll = Payroll::findOrFail($id);

    //     // Ambil current_salary dari employee
    //     $employee = Employee::findOrFail($request->employee_id);
    //     $basic_salary = $employee->current_salary;

    //     // Set nilai default untuk tunjangan, lembur, dan potongan jika tidak diisi
    //     $allowance = $request->allowance ?? 0;
    //     $overtime = $request->overtime ?? 0;
    //     $deductions = $request->deductions ?? 0;

    //     // Hitung total gaji
    //     $total_salary = $basic_salary + $allowance + $overtime - $deductions;

    //     // Update data payroll
    //     $payroll->update([
    //         'employee_id' => $request->employee_id,
    //         'allowance' => $allowance,
    //         'overtime' => $overtime,
    //         'deductions' => $deductions,
    //         'total_salary' => $total_salary,
    //     ]);

    //     return redirect()->route('payroll.index')->with('success', 'Payroll berhasil diperbarui');
    // }

    // public function destroy($id)
    // {
    //     // Ambil payroll berdasarkan ID
    //     $payroll = Payroll::findOrFail($id);
    //     $payroll->delete(); // Hapus payroll

    //     return redirect()->route('payroll.index')->with('success', 'Payroll berhasil dihapus');
    // }
}
