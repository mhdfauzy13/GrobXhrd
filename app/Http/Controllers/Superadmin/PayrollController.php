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

    // public function index1(Request $request)
    // {
    //     $month = $request->input('month', date('m'));
    //     $year = $request->input('year', date('Y'));

    //     // Ambil potongan gaji dari tabel salary_deduction jika ada
    //     $salaryDeduction = SalaryDeduction::first();
    //     $lateDeduction = $salaryDeduction->late_deduction ?? 0;
    //     $earlyDeduction = $salaryDeduction->early_deduction ?? 0;

    //     $payrolls = Payroll::with(['employee', 'attandanceRecap', 'workdaySetting', 'overtime', 'offRequests'])
    //         ->whereHas('attandanceRecap', function ($query) use ($month, $year) {
    //             $query->whereMonth('month', $month)
    //                   ->whereYear('month', $year);
    //         })
    //         ->get()
    //         ->map(function ($payroll) use ($lateDeduction, $earlyDeduction) {
    //             $totalDaysWorked = $payroll->attandanceRecap->total_present ?? 0;
    //             $totalDaysOff = $payroll->offRequests
    //                 ->where('status', 'approved')
    //                 ->sum(function ($offRequest) {
    //                     $start = \Carbon\Carbon::parse($offRequest->start_event);
    //                     $end = \Carbon\Carbon::parse($offRequest->end_event);
    //                     return $start->diffInDays($end) + 1;
    //                 });
    //             $totalLateCheckIn = $payroll->attandanceRecap->total_late ?? 0;
    //             $totalEarlyCheckOut = $payroll->attandanceRecap->total_early ?? 0;
    //             $effectiveWorkDays = $payroll->workdaySetting->monthly_workdays ?? 0;
    //             $currentSalary = $payroll->employee->current_salary ?? 0;

    //             $hourlyRate = $currentSalary / ($effectiveWorkDays * ($payroll->employee->check_out_time - $payroll->employee->check_in_time));
    //             $overtimePay = $payroll->overtime->sum(function ($overtime) use ($hourlyRate) {
    //                 return $hourlyRate * $overtime->duration;
    //             });

    //             $totalSalary = ($currentSalary / $effectiveWorkDays) * $totalDaysWorked + $overtimePay;

    //             // Hitung potongan berdasarkan total keterlambatan dan pulang awal, jika ada
    //             $totalLateDeduction = $totalLateCheckIn * $lateDeduction;
    //             $totalEarlyDeduction = $totalEarlyCheckOut * $earlyDeduction;
    //             $totalSalary -= ($totalLateDeduction + $totalEarlyDeduction);

    //             // Atur nilai untuk tampilan
    //             $payroll->total_days_worked = $totalDaysWorked;
    //             $payroll->total_days_off = $totalDaysOff;
    //             $payroll->total_late_check_in = $totalLateCheckIn;
    //             $payroll->total_early_check_out = $totalEarlyCheckOut;
    //             $payroll->effective_work_days = $effectiveWorkDays;
    //             $payroll->current_salary = $currentSalary;
    //             $payroll->overtime_pay = $overtimePay;
    //             $payroll->total_salary = $totalSalary;
    //             $payroll->total_late_deduction = $totalLateDeduction;
    //             $payroll->total_early_deduction = $totalEarlyDeduction;

    //             return $payroll;
    //         });

    //     return view('superadmin.payroll.index', compact('payrolls'));
    // }


    public function index()
    {
        // Ambil data semua employee
        $employees = Employee::all();

        $payrollData = $employees->map(function ($employee) {

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
                // Ambil hanya tanggal tanpa memperhatikan waktu
                $start = Carbon::parse($offrequest->start_event)->toDateString();
                $end = Carbon::parse($offrequest->end_event)->toDateString();
        
                // Hitung selisih hari berdasarkan tanggal
                return Carbon::parse($start)->diffInDays($end) + 1; // Menambahkan 1 agar termasuk hari pertama
            });

            // Ambil data monthly workdays dari workday_settings
            $workdaySetting = WorkdaySetting::first();
            $monthlyWorkdays = $workdaySetting ? $workdaySetting->monthly_workdays : 0;

            return [
                'employee_name' => $employee->first_name . ' ' . $employee->last_name,
                'current_salary' => $employee->current_salary,
                'total_days_worked' => $totalWorkedDays,
                'total_days_off' => $totalDaysOff,
                'total_late_check_in' => $totalLate,
                'total_early_check_out' => $totalEarly,
                'monthly_workdays' => $monthlyWorkdays,
            ];
        });
        // dd($payrollData);

        // Kirim data payroll ke view
        return view('superadmin.payroll.index', compact('payrollData'));
    }


    public function validatePayroll($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->validation_status = 1;
        $payroll->save();

        return response()->json(['message' => 'Payroll validated successfully']);
    }

    public function exportToCsv()
    {
        // Mengambil payroll yang sudah tervalidasi
        $validatedPayrolls = Payroll::where('validation_status', 1)
            ->with(['employee', 'attandanceRecap', 'workdaySetting', 'overtime', 'offRequests']) // Pastikan relasi sudah dimuat
            ->get();

        // Membuat header CSV
        $csvData = "Employee Name,Total Days Worked,Total Days Off,Total Late Check In,Total Early Check Out,Effective Work Days,Current Salary,Overtime Pay,Total Salary\n";

        // Menghitung dan menambahkan data tiap payroll ke CSV
        foreach ($validatedPayrolls as $payroll) {
            // Menghitung Total Days Off (jumlah hari cuti yang disetujui)
            $totalDaysOff = $payroll->offRequests->where('status', 'approved')->sum(function ($offRequest) {
                $start = \Carbon\Carbon::parse($offRequest->start_event);
                $end = \Carbon\Carbon::parse($offRequest->end_event);
                return $start->diffInDays($end) + 1; // +1 untuk termasuk hari mulai
            });

            // Menghitung Overtime Pay
            $hourlyRate = $payroll->employee->current_salary / ($payroll->workdaySetting->monthly_workdays * $payroll->employee->check_in_time->diffInHours($payroll->employee->check_out_time));
            $overtimePay = $payroll->overtime->sum(function ($overtime) use ($hourlyRate) {
                return $hourlyRate * $overtime->duration;
            });

            // Menghitung Total Salary
            $totalSalary = ($payroll->employee->current_salary / $payroll->workdaySetting->monthly_workdays) * $payroll->attandanceRecap->total_present + $overtimePay;

            // Menambahkan data payroll ke CSV
            $csvData .= "{$payroll->employee->first_name} {$payroll->employee->last_name}," // Employee Name
                . "{$payroll->attandanceRecap->total_present}," // Total Days Worked
                . "{$totalDaysOff}," // Total Days Off
                . "{$payroll->attandanceRecap->total_late}," // Total Late Check In
                . "{$payroll->attandanceRecap->total_early}," // Total Early Check Out
                . "{$payroll->workdaySetting->monthly_workdays}," // Effective Work Days
                . "{$payroll->employee->current_salary}," // Current Salary
                . "{$overtimePay}," // Overtime Pay
                . "{$totalSalary}\n"; // Total Salary
        }

        // Menentukan nama file CSV
        $fileName = "payroll_report_" . now()->format('Y_m_d') . ".csv";

        // Menyusun dan mengirim file CSV ke user
        return response()->streamDownload(function () use ($csvData) {
            echo $csvData;
        }, $fileName);
    }
}
