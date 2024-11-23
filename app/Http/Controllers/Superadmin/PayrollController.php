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
use Illuminate\Support\Facades\Response;

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
                    'status' => 'pending',
                    // 'month' => now()->format('F'), 
                ]
            );

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

        return view('superadmin.payroll.index', compact('payrollData'));
    }


    // public function updateValidationStatus(Request $request)
    // {
    //     $request->validate([
    //         'employee_id' => 'required|exists:payrolls,id',
    //         'status' => 'required|in:approved,pending',
    //     ]);

    //     $payroll = Payroll::find($request->id);
    //     $payroll->update(['status' => $request->status]);

    //     return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    // }


    // public function updateValidationStatus(Request $request, $id)
    // {
    //     // Validasi input
    //     $validatedData = $request->validate([
    //         'status' => 'required|in:approved,rejected',
    //     ]);

    //     // Ambil data payroll berdasarkan ID
    //     $payroll = Payroll::findOrFail($id);

    //     // Cek apakah status sudah sama
    //     if ($payroll->status === $validatedData['status']) {
    //         return redirect()
    //             ->route('payroll.index')
    //             ->with('info', 'No changes were made. Payroll already has the status: ' . $validatedData['status'] . '.');
    //     }

    //     // Perbarui status validasi
    //     $payroll->status = $validatedData['status'];
    //     $payroll->save();

    //     // Redirect dengan pesan sukses
    //     return redirect()
    //         ->route('payroll.index')
    //         ->with('success', 'Payroll has been ' . $validatedData['status'] . ' successfully.');
    // }


    public function approve($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->update([
            'status' => 'approved',
            'approved_at' => now(), // Tambahkan waktu persetujuan
        ]);

        // dd($payroll->toArray()); 


        return redirect()->back()->with('success', 'Payroll approved successfully!');
    }

    public function decline($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->update([
            'status' => 'pending',
            'approved_at' => null, // Hapus waktu persetujuan
        ]);

        return redirect()->back()->with('success', 'Payroll status reset to pending.');
    }


    public function exportCsv()
    {
        $approvedPayrolls = Payroll::where('status', 'approved')->get();

        // Siapkan header CSV
        $csvHeader = [
            'Employee ID',
            'Employee Name',
            'Current Salary',
            'Total Days Worked',
            'Total Days Off',
            'Total Late Check-in',
            'Total Early Check-out',
            'Monthly Workdays',
            'Overtime Pay',
            'Total Payroll',
            'Approved At',
        ];

        // Siapkan data CSV
        $csvData = $approvedPayrolls->map(function ($payroll) {
            return [
                $payroll->employee_id,
                $payroll->employee_name,
                $payroll->current_salary,
                $payroll->total_days_worked,
                $payroll->total_days_off,
                $payroll->total_late_check_in,
                $payroll->total_early_check_out,
                $payroll->monthly_workdays,
                $payroll->overtime_pay,
                $payroll->total_payroll,
                $payroll->approved_at,
            ];
        });

        // Konversi ke CSV string
        $csv = implode(",", $csvHeader) . "\n";
        foreach ($csvData as $row) {
            $csv .= implode(",", $row) . "\n";
        }

        // Kirim file CSV
        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="payroll.csv"',
        ]);
    }

    // public function exportToCsv()
    // {
    //     $approvedPayrolls = Payroll::where('status', 'approved')->get();

    //     $csvFileName = 'payroll_export_' . now()->format('Ymd_His') . '.csv';
    //     $headers = [
    //         'Content-Type' => 'text/csv',
    //         'Content-Disposition' => "attachment; filename=\"$csvFileName\"",
    //     ];

    //     $columns = [
    //         'Employee Name',
    //         'Current Salary',
    //         'Total Days Worked',
    //         'Total Days Off',
    //         'Total Late Check In',
    //         'Total Early Check Out',
    //         'Effective Work Days',
    //         'Overtime Pay',
    //         'Total Salary',
    //         'Validation Status',
    //     ];

    //     $callback = function () use ($approvedPayrolls, $columns) {
    //         $file = fopen('php://output', 'w');
    //         fputcsv($file, $columns);

    //         foreach ($approvedPayrolls as $payroll) {
    //             fputcsv($file, [
    //                 $payroll->employee_name,
    //                 $payroll->current_salary,
    //                 $payroll->total_days_worked,
    //                 $payroll->total_days_off,
    //                 $payroll->total_late_check_in,
    //                 $payroll->total_early_check_out,
    //                 $payroll->monthly_workdays,
    //                 $payroll->overtime_pay,
    //                 $payroll->total_payroll,
    //                 ucfirst($payroll->status),
    //             ]);
    //         }

    //         fclose($file);
    //     };

    //     return response()->stream($callback, 200, $headers);
    // }



    // public function exportToCsv()
    // {

    //     // Ambil data payroll yang sudah disetujui (approved)
    //     $payrollData = Payroll::where('status', 'approved')->get();

    //     // Jika tidak ada data yang disetujui, beri pesan atau ekspor file kosong
    //     if ($payrollData->isEmpty()) {
    //         return response()->stream(function () {
    //             echo "No approved payroll data to export.";
    //         }, 200, [
    //             'Content-Type' => 'text/csv',
    //             'Content-Disposition' => 'attachment; filename="payroll.csv"',
    //         ]);
    //     }

    //     // Membuat file CSV
    //     $handle = fopen('php://output', 'w');
    //     fputcsv($handle, ['Employee Name', 'Current Salary', 'Total Days Worked', 'Total Days Off', 'Total Late Check In', 'Total Early Check Out', 'Effective Work Days', 'Overtime Pay', 'Total Salary']); // Menulis header CSV

    //     foreach ($payrollData as $data) {
    //         fputcsv($handle, [
    //             $data->employee_name,
    //             $data->current_salary,
    //             $data->total_days_worked,
    //             $data->total_days_off,
    //             $data->total_late_check_in,
    //             $data->total_early_check_out,
    //             $data->monthly_workdays,
    //             $data->overtime_pay,
    //             $data->total_payroll,
    //         ]);
    //     }

    //     fclose($handle);

    //     return response()->stream(function () use ($handle) {
    //         fclose($handle);
    //     }, 200, [
    //         'Content-Type' => 'text/csv',
    //         'Content-Disposition' => 'attachment; filename="payroll.csv"',
    //     ]);
    // }


}


    // public function updateValidationStatus(Request $request, $id)
    // {
    //     $payroll = Payroll::findOrFail($id);

    //     // Memastikan hanya status yang valid yang dapat diperbarui
    //     if ($request->status == 'approved') {
    //         $payroll->validation_status = 'approved';
    //     } elseif ($request->status == 'declined') {
    //         $payroll->validation_status = 'declined';
    //     }

    //     $payroll->save();

    //     return redirect()->route('payroll.index')->with('success', 'Payroll status updated successfully');
    // }




    // public function updateValidationStatus(Request $request, $id)
    // {
    //     try {
    //         // Temukan payroll berdasarkan ID
    //         $payroll = Payroll::findOrFail($id);

    //         // Validasi input status, pastikan status ada dalam input dan valid
    //         $status = $request->input('validation_status');
    //         if (!in_array($status, ['approved', 'declined'])) {
    //             return redirect()->back()->with('error', 'Invalid status: ' . $status);
    //         }

    //         // Perbarui status payroll
    //         $payroll->validation_status = $status;
    //         $payroll->save();

    //         // Redirect kembali ke daftar payroll dengan pesan sukses
    //         return redirect()->route('payroll.index')->with('success', 'Payroll status updated successfully.');
    //     } catch (\Exception $e) {
    //         // Jika terjadi error, tampilkan pesan error
    //         return redirect()->back()->with('error', 'Failed to update payroll status: ' . $e->getMessage());
    //     }
    // }




    // public function exportToCSV()
    // {
    //     // Ambil data payroll dengan status approved
    //     $payrollData = Payroll::where('validation_status', 'approved')->get();

    //     // Cek jika tidak ada data yang bisa diekspor
    //     if ($payrollData->isEmpty()) {
    //         return redirect()->route('payroll.index')->with('error', 'No approved payroll data to export.');
    //     }

    //     // Siapkan header CSV
    //     $headers = ['ID', 'Employee Name', 'Current Salary', 'Total Worked Days', 'Total Days Off', 'Total Late', 'Total Early', 'Monthly Workdays', 'Overtime Pay', 'Total Payroll'];

    //     // Membuat file CSV
    //     $csvFileName = 'payroll_approved_data_' . now()->format('Ymd_His') . '.csv';
    //     $filePath = storage_path('app/public/' . $csvFileName);

    //     $file = fopen($filePath, 'w');
    //     fputcsv($file, $headers);

    //     // Menulis data ke file CSV
    //     foreach ($payrollData as $data) {
    //         fputcsv($file, [
    //             $data->id,
    //             $data->employee_name,
    //             $data->current_salary,
    //             $data->total_days_worked,
    //             $data->total_days_off,
    //             $data->total_late_check_in,
    //             $data->total_early_check_out,
    //             $data->monthly_workdays,
    //             $data->overtime_pay,
    //             $data->total_payroll,
    //         ]);
    //     }

    //     fclose($file);

    //     // Mengirimkan file CSV sebagai response untuk diunduh
    //     return response()->download($filePath)->deleteFileAfterSend(true);
    // }