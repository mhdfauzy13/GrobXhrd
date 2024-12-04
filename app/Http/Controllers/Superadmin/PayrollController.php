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
use Illuminate\Support\Facades\Log;

class PayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payroll.index')->only(['index', 'updateValidationStatus', 'exportToCsv']);
    }

    public function index()
    {
        // Ambil data semua employee
        $employees = Employee::all();

        // Ambil data salary deduction
        $salaryDeduction = SalaryDeduction::first();
        $lateDeduction = $salaryDeduction ? $salaryDeduction->late_deduction : 0;
        $earlyDeduction = $salaryDeduction ? $salaryDeduction->early_deduction : 0;

        $payrollData = $employees->map(function ($employee) use ($lateDeduction, $earlyDeduction) {

            // Pastikan user_id valid
            if (!$employee->user_id) {
                // Jika user_id tidak valid, skip proses dan log
                Log::warning("Invalid user_id for employee {$employee->employee_id}");
                return null; // Skip entry jika user_id tidak ada
            }

            // Ambil data dari AttendanceRecap untuk employee ini
            $attendanceRecap = AttandanceRecap::where('user_id', $employee->employee_id)->first();

            // Hitung total hari kerja, total terlambat, dan total awal pulang
            $totalWorkedDays = $attendanceRecap ? $attendanceRecap->total_present : 0;
            $totalLate = $attendanceRecap ? $attendanceRecap->total_late : 0;
            $totalEarly = $attendanceRecap ? $attendanceRecap->total_early : 0;

            // Hitung total hari cuti
            $totalDaysOff = Offrequest::where('user_id', $employee->employee_id)
                ->where('status', 'approved')
                ->get()
                ->sum(function ($offrequest) {
                    $start = Carbon::parse($offrequest->start_event)->toDateString();
                    $end = Carbon::parse($offrequest->end_event)->toDateString();
                    return Carbon::parse($start)->diffInDays($end) + 1; // Menambahkan 1 agar termasuk hari pertama
                });

            // Ambil settingan hari kerja bulanan
            $workdaySetting = WorkdaySetting::first();
            $monthlyWorkdays = $workdaySetting ? $workdaySetting->monthly_workdays : 0;

            // Hitung durasi kerja per hari dalam jam
            $workDurationInHours = ($employee->check_in_time && $employee->check_out_time)
                ? Carbon::parse($employee->check_in_time)->diffInHours(Carbon::parse($employee->check_out_time))
                : 8; // Default 8 jam

            // Hitung gaji per jam
            $dailySalary = $monthlyWorkdays > 0 ? $employee->current_salary / $monthlyWorkdays : 0;
            $hourlyRate = $workDurationInHours > 0 ? $dailySalary / $workDurationInHours : 0;

            // Hitung total lembur
            $overtimeData = Overtime::where('user_id', $employee->employee_id)
                ->where('status', 'approved')
                ->get();
            $totalOvertimeHours = $overtimeData->sum('duration');
            $overtimePay = $totalOvertimeHours * $hourlyRate;

            // Hitung total deduksi berdasarkan terlambat dan awal pulang
            $totalLateDeduction = $totalLate * $lateDeduction;
            $totalEarlyDeduction = $totalEarly * $earlyDeduction;
            $totalDeductions = $totalLateDeduction + $totalEarlyDeduction;

            // Hitung total payroll
            $baseSalary = $totalWorkedDays * $dailySalary;
            $totalPayroll = $baseSalary - $totalDeductions + $overtimePay;

            // Update atau buat data payroll
            Payroll::updateOrCreate(
                ['user_id' => $employee->user_id],
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
                    'status' => 'pending',
                    // 'month' => now()->format('F'), 
                ]
            );

            // Return data payroll untuk tampilan
            return [
                'id' => $employee->employee_id,
                'employee_name' => $employee->first_name . ' ' . $employee->last_name,
                'current_salary' => $employee->current_salary,
                'total_days_worked' => $totalWorkedDays,
                'total_days_off' => $totalDaysOff,
                'total_late_check_in' => $totalLate,
                'total_early_check_out' => $totalEarly,
                'monthly_workdays' => $monthlyWorkdays,
                'overtime_pay' => $overtimePay,
                'total_payroll' => $totalPayroll,
                'status' => 'pending',
            ];
        });

        // Filter data payroll yang valid (tidak null)
        $payrollData = $payrollData->filter(function ($item) {
            return $item !== null;
        });

        // Tampilkan view dengan data payroll yang sudah dihitung
        return view('superadmin.payroll.index', compact('payrollData'));
    }

    public function updateValidationStatus(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        // Ambil data payroll berdasarkan ID
        $payroll = Payroll::findOrFail($id);

        // Cek apakah status sudah sama
        if ($payroll->status === $validatedData['status']) {
            return redirect()
                ->route('payroll.index')
                ->with('info', 'No changes were made. Payroll already has the status: ' . $validatedData['status'] . '.');
        }

        // Perbarui status validasi
        $payroll->status = $validatedData['status'];
        $payroll->save();

        // Redirect dengan pesan sukses
        return redirect()
            ->route('payroll.index')
            ->with('success', 'Payroll has been ' . $validatedData['status'] . ' successfully.');
    }

    public function exportToCsv()
    {
        // Ambil data payroll yang sudah disetujui (approved)
        $payrollData = Payroll::where('status', 'approved')->get();

        // Jika tidak ada data yang disetujui, beri pesan atau ekspor file kosong
        if ($payrollData->isEmpty()) {
            return response()->stream(function () {
                echo "No approved payroll data to export.";
            }, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="payroll.csv"',
            ]);
        }

        // Membuat file CSV
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Employee Name', 'Current Salary', 'Total Days Worked', 'Total Days Off', 'Total Late Check In', 'Total Early Check Out', 'Effective Work Days', 'Overtime Pay', 'Total Salary']); // Menulis header CSV

        foreach ($payrollData as $data) {
            fputcsv($handle, [
                $data->employee_name,
                $data->current_salary,
                $data->total_days_worked,
                $data->total_days_off,
                $data->total_late_check_in,
                $data->total_early_check_out,
                $data->monthly_workdays,
                $data->overtime_pay,
                $data->total_payroll,
            ]);
        }

        fclose($handle);

        return response()->stream(function () use ($handle) {
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="payroll.csv"',
        ]);
    }
}
