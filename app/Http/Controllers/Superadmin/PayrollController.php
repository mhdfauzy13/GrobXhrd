<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\AttandanceRecap;
use App\Models\Employee;
use App\Models\Offrequest;
use App\Models\Overtime;
use App\Models\Payroll;
use App\Models\SalaryDeduction;
use App\Models\WorkdaySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payroll.index')->only(['index', 'validatePayroll', 'exportToCsv']);
    }



    public function index()
    {
        // Ambil data semua employee
        $employees = Employee::all();

        $salaryDeduction = SalaryDeduction::first();
        $lateDeduction = $salaryDeduction ? $salaryDeduction->late_deduction : 0;
        $earlyDeduction = $salaryDeduction ? $salaryDeduction->early_deduction : 0;

        $payrollData = $employees->map(function ($employee) use ($lateDeduction, $earlyDeduction) {

            // Ambil data dari AttendanceRecap untuk employee ini
            $attendanceRecap = AttandanceRecap::where('employee_id', $employee->employee_id)
                //    ->where('month', now()->format('F')) // Mengambil data bulan ini
                ->first();

            $totalWorkedDays = $attendanceRecap ? $attendanceRecap->total_present : 0;
            $totalLate = $attendanceRecap ? $attendanceRecap->total_late : 0;
            $totalEarly = $attendanceRecap ? $attendanceRecap->total_early : 0;


            $totalDaysOff = Offrequest::where('employee_id', $employee->employee_id)
                ->where('status', 'approved')
                ->get()
                ->sum(function ($offrequest) {
                    $start = Carbon::parse($offrequest->start_event)->toDateString();
                    $end = Carbon::parse($offrequest->end_event)->toDateString();

                    // Hitung selisih hari berdasarkan tanggal
                    return Carbon::parse($start)->diffInDays($end) + 1; // Menambahkan 1 agar termasuk hari pertama
                });

            // Ambil data monthly workdays dari workday_settings
            $workdaySetting = WorkdaySetting::first();
            $monthlyWorkdays = $workdaySetting ? $workdaySetting->monthly_workdays : 0;

            // Hitung durasi kerja per hari (dalam jam)
            if ($employee->check_in_time && $employee->check_out_time) {
                $workDurationInHours = Carbon::parse($employee->check_in_time)->diffInHours(Carbon::parse($employee->check_out_time));
            } else {
                $workDurationInHours = 8; // Default 
            }

            // Hitung gaji per jam
            $dailySalary = $monthlyWorkdays > 0 ? $employee->current_salary / $monthlyWorkdays : 0;
            $hourlyRate = $workDurationInHours > 0 ? $dailySalary / $workDurationInHours : 0;

            // Hitung total overtime
            $overtimeData = Overtime::where('employee_id', $employee->employee_id)->get();
            $totalOvertimeHours = $overtimeData->sum('duration');
            $overtimePay = $totalOvertimeHours * $hourlyRate;

            // Hitung total deduction berdasarkan total late dan early
            $totalLateDeduction = $totalLate * $lateDeduction;
            $totalEarlyDeduction = $totalEarly * $earlyDeduction;
            $totalDeductions = $totalLateDeduction + $totalEarlyDeduction;

            // Hitung total payroll (gaji dasar, deduksi, lembur)
            $baseSalary = $totalWorkedDays * $dailySalary;
            $totalPayroll = $baseSalary - $totalDeductions + $overtimePay;

            Payroll::updateOrCreate(
                ['employee_id' => $employee->employee_id],
                [
                    'employee_name' => $employee->first_name . ' ' . $employee->last_name,
                    'current_salary' => $employee->current_salary,
                    'total_days_worked' => $totalWorkedDays,
                    'total_days_off' => $totalDaysOff,
                    'total_late_check_in' => $totalLate,
                    'total_early_check_out' => $totalEarly,
                    'monthly_workdays' => $monthlyWorkdays,
                    'overtime_pay' => $overtimePay,
                    'total_payroll' => $totalPayroll,
                    'validation_status' => 'pending',
                    // 'month' => now()->format('F'), 
                ]
            );

            return [
                'employee_name' => $employee->first_name . ' ' . $employee->last_name,
                'current_salary' => $employee->current_salary,
                'total_days_worked' => $totalWorkedDays,
                'total_days_off' => $totalDaysOff,
                'total_late_check_in' => $totalLate,
                'total_early_check_out' => $totalEarly,
                'monthly_workdays' => $monthlyWorkdays,
                'overtime_pay' => $overtimePay,
                'total_payroll' => $totalPayroll,
            ];
        });
        // dd($payrollData);


        return view('superadmin.payroll.index', compact('payrollData'));
    }


    public function updateValidationStatus(Request $request, $id)
    {
        $payroll = Payroll::findOrFail($id);

        // Validate the status input
        $status = $request->input('status');
        if (!in_array($status, ['approved', 'declined'])) {
            return redirect()->back()->with('error', 'Invalid status.');
        }

        // Update the status
        $payroll->validation_status = $status;
        $payroll->save();

        return redirect()->route('payroll.index')->with('success', 'Payroll status updated successfully.');
    }


    // Use the appropriate route for exporting payroll data
    public function exportPayroll()
    {
        $payrollData = Payroll::where('validation_status', 'approved')->get(); // Optional: Export only approved data

        // Export to CSV
        return Excel::download(new PayrollExport($payrollData), 'payrolls.csv');
    }
}
