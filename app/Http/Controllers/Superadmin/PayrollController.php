<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Attandance;
use App\Models\AttandanceRecap;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\WorkdaySetting;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payroll.index')->only(['index', 'calculatePayroll', 'validatePayroll']);
        $this->middleware('permission:payroll.create')->only(['create', 'store']);
        $this->middleware('permission:payroll.edit')->only(['edit', 'update']);
        $this->middleware('permission:payroll.delete')->only('destroy');
    }


    // public function index()
    // {
    //     $payrolls = Payroll::with('employee')->get();
    //     return view('Superadmin.payroll.index', compact('payrolls'));
    // }

    public function index(Request $request)
    {
        // Ambil bulan dan tahun dari request
        $month = $request->input('month');
        $year = $request->input('year');

        // Query untuk mengambil data payroll dengan filter
        $payrolls = Payroll::with('employee') // Pastikan untuk mengaitkan data employee
            ->when($month, function ($query) use ($month) {
                return $query->whereMonth('created_at', $month);
            })
            ->when($year, function ($query) use ($year) {
                return $query->whereYear('created_at', $year);
            })
            ->get(); // Ambil semua data

        return view('Superadmin.payroll.index', compact('payrolls', 'month', 'year'));
    }

    public function create()
    {
        $employees = Employee::all(); // Ambil data karyawan dari tabel employees
        return view('Superadmin.payroll.create', compact('employees'));
    }

    public function calculatePayroll(Request $request)
    {
        // Validasi input
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $month = $request->input('month');
        $workDaySetting = WorkDaySetting::where('month', $month)->first();
        $totalEffectiveWorkdays = $workDaySetting ? $workDaySetting->total_days : 0;
        $employees = Employee::all();
        $payrollData = [];

        foreach ($employees as $employee) {
            // Ambil data rekap absensi
            $attendanceRecap = AttandanceRecap::where('employee_id', $employee->employee_id)
                ->where('month', $month)->first();
            $totalPresent = $attendanceRecap ? $attendanceRecap->total_present : 0;
            $totalAbsences = $attendanceRecap ? $attendanceRecap->total_absent : 0;

            // Hitung total gaji
            $totalSalary = ($totalPresent / $totalEffectiveWorkdays) * $employee->current_salary - ($totalAbsences * ($employee->current_salary / $totalEffectiveWorkdays));

            // Simpan data payroll ke tabel payrolls
            $payroll = new Payroll();
            $payroll->employee_id = $employee->employee_id;
            $payroll->total_salary = $totalSalary;
            $payroll->is_validated = false; // Status validasi awal
            $payroll->save();

            // Simpan hasil ke array untuk ditampilkan
            $payrollData[] = [
                'employee_id' => $employee->employee_id,
                'name' => $employee->name,
                'total_present' => $totalPresent,
                'total_absent' => $totalAbsences,
                'current_salary' => $employee->current_salary,
                'total_salary' => $totalSalary,
                'is_validated' => false // Menyimpan status validasi di array
            ];
        }

        return view('payroll.result', compact('payrollData', 'month'));
    }

    public function validatePayroll(Request $request, $employee_id)
    {
        $request->validate([
            'is_validated' => 'required|boolean',
        ]);

        $payroll = Payroll::where('employee_id', $employee_id)->latest()->first();
        if ($payroll) {
            $payroll->is_validated = $request->input('is_validated');
            $payroll->save();
        }

        return redirect()->back()->with('success', 'Status validasi berhasil diperbarui.');
    }
}
