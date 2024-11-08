<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\AttandanceRecap;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\Payroll;
use App\Models\WorkdaySetting;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payroll.index')->only(['index', 'validatePayroll', 'exportToCsv']);
    }

    public function index(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
    
        $payrolls = Payroll::with(['employee', 'attandanceRecap', 'workdaySetting', 'overtime', 'offRequests'])
            ->whereHas('attandanceRecap', function ($query) use ($month, $year) {
                $query->whereMonth('month', $month)
                      ->whereYear('month', $year);
            })
            ->get()
            ->map(function ($payroll) {
                // Menghitung total days worked
                $totalDaysWorked = $payroll->attandanceRecap->total_present ?? 0;
    
                // Menghitung total days off berdasarkan offRequests yang disetujui
                $totalDaysOff = $payroll->offRequests
                    ->where('status', 'approved')
                    ->sum(function ($offRequest) {
                        $start = \Carbon\Carbon::parse($offRequest->start_event);
                        $end = \Carbon\Carbon::parse($offRequest->end_event);
                        return $start->diffInDays($end) + 1; // +1 untuk menyertakan hari mulai
                    });
    
                // Menghitung total days late dan total early check out
                $totalLateCheckIn = $payroll->attandanceRecap->total_late ?? 0;
                $totalEarlyCheckOut = $payroll->attandanceRecap->total_early ?? 0;
    
                // Menghitung effective work days
                $effectiveWorkDays = $payroll->workdaySetting->monthly_workdays ?? 0;
    
                // Menghitung current salary
                $currentSalary = $payroll->employee->current_salary ?? 0;
    
                // Menghitung overtime pay
                $hourlyRate = $currentSalary / ($effectiveWorkDays * ($payroll->employee->check_out_time - $payroll->employee->check_in_time));
                $overtimePay = $payroll->overtime->sum(function ($overtime) use ($hourlyRate) {
                    return $hourlyRate * $overtime->duration;
                });
    
                // Menghitung total salary
                $totalSalary = ($currentSalary / $effectiveWorkDays) * $totalDaysWorked + $overtimePay;
    
                // Mengatur nilai untuk tampilan
                $payroll->total_days_worked = $totalDaysWorked;
                $payroll->total_days_off = $totalDaysOff;
                $payroll->total_late_check_in = $totalLateCheckIn;
                $payroll->total_early_check_out = $totalEarlyCheckOut;
                $payroll->effective_work_days = $effectiveWorkDays;
                $payroll->current_salary = $currentSalary;
                $payroll->overtime_pay = $overtimePay;
                $payroll->total_salary = $totalSalary;
    
                return $payroll;
            });
    
        return view('superadmin.payroll.index', compact('payrolls'));
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
