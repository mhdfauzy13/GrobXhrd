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
use Illuminate\Support\Facades\Response;

class PayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payroll.index')->only(['index', 'approve']);
        $this->middleware('permission:payroll.export')->only(['exportToCsv']);

    }

    // public function index()
    // {
    //     // Ambil data semua employee
    //     $employees = Employee::all();
    //     // dd($employees);

    //     // Ambil data salary deduction
    //     $salaryDeduction = SalaryDeduction::first();
    //     $lateDeduction = $salaryDeduction ? $salaryDeduction->late_deduction : 0;
    //     $earlyDeduction = $salaryDeduction ? $salaryDeduction->early_deduction : 0;

    //     $payrollData = $employees->map(function ($employee) use ($lateDeduction, $earlyDeduction) {

    //         // Ambil data dari AttendanceRecap untuk employee ini
    //         // $attendanceRecap = AttandanceRecap::where('user_id', $employee->employee_id)->first();
    //         $attendanceRecap = AttandanceRecap::where('employee_id', $employee->employee_id)->first();
    //         $attendanceRecap = $employee->attandanceRecap()->first();

    //         // dd($attendanceRecap);

    //         // Hitung total hari kerja, total terlambat, dan total awal pulang
    //         $totalWorkedDays = $attendanceRecap ? $attendanceRecap->total_present : 0;
    //         $totalLate = $attendanceRecap ? $attendanceRecap->total_late : 0;
    //         $totalEarly = $attendanceRecap ? $attendanceRecap->total_early : 0;

    //         // Hitung total hari cuti
    //         $totalDaysOff = Offrequest::where('user_id', $employee->employee_id)
    //             ->where('status', 'approved')
    //             ->get()
    //             ->sum(function ($offrequest) {
    //                 $start = Carbon::parse($offrequest->start_event)->toDateString();
    //                 $end = Carbon::parse($offrequest->end_event)->toDateString();
    //                 return Carbon::parse($start)->diffInDays($end) + 1; // Menambahkan 1 agar termasuk hari pertama
    //             });

    //         // Ambil settingan hari kerja bulanan
    // $workdaySetting = WorkdaySetting::first();
    // $monthlyWorkdays = $workdaySetting ? $workdaySetting->monthly_workdays : 0;

    // // Hitung durasi kerja per hari dalam jam
    // $workDurationInHours = ($employee->check_in_time && $employee->check_out_time)
    //     ? Carbon::parse($employee->check_in_time)->diffInHours(Carbon::parse($employee->check_out_time))
    //     : 8; // Default 8 jam

    // // Hitung gaji per jam
    // $dailySalary = $monthlyWorkdays > 0 ? $employee->current_salary / $monthlyWorkdays : 0;
    // $hourlyRate = $workDurationInHours > 0 ? $dailySalary / $workDurationInHours : 0;

    // // Hitung total overtime
    // $overtimeData = Overtime::where('user_id', $employee->user_id)
    //     ->where('status', 'approved') // Hanya ambil yang approved
    //     ->get();
    // $totalOvertimeHours = $overtimeData->sum('duration');
    // $hourlyRate = $employee->hourly_rate;
    // $overtimePay = $totalOvertimeHours * $hourlyRate;

    // // Hitung total deduksi berdasarkan terlambat dan awal pulang
    // $totalLateDeduction = $totalLate * $lateDeduction;
    // $totalEarlyDeduction = $totalEarly * $earlyDeduction;
    // $totalDeductions = $totalLateDeduction + $totalEarlyDeduction;

    //         // Hitung total payroll
    //         $baseSalary = $totalWorkedDays * $dailySalary;
    //         $totalPayroll = $baseSalary - $totalDeductions + $overtimePay;

    //         // Update atau buat data payroll
    //         Payroll::updateOrCreate(
    //             ['user_id' => $employee->user_id],
    //             [
    //                 'employee_name' => $employee->first_name . ' ' . $employee->last_name,
    //                 'current_salary' => $employee->current_salary,
    //                 'total_days_worked' => $totalWorkedDays,
    //                 'total_days_off' => $totalDaysOff,
    //                 'total_late_check_in' => $totalLate,
    //                 'total_early_check_out' => $totalEarly,
    //                 'monthly_workdays' => $monthlyWorkdays,
    //                 'overtime_pay' => $overtimePay,
    //                 'total_payroll' => $totalPayroll,
    //                 'status' => 'pending',
    //                 // 'month' => now()->format('F'), 
    //             ]
    //         );

    //         // Return data payroll untuk tampilan
    //         return [
    //             'id' => $employee->employee_id,
    //             'employee_name' => $employee->first_name . ' ' . $employee->last_name,
    //             'current_salary' => $employee->current_salary,
    //             'total_days_worked' => $totalWorkedDays,
    //             'total_days_off' => $totalDaysOff,
    //             'total_late_check_in' => $totalLate,
    //             'total_early_check_out' => $totalEarly,
    //             'monthly_workdays' => $monthlyWorkdays,
    //             'overtime_pay' => $overtimePay,
    //             'total_payroll' => $totalPayroll,
    //             'status' => 'pending',
    //         ];
    //     });


    //     // Filter data payroll yang valid (tidak null)
    //     $payrollData = $payrollData->filter(function ($item) {
    //         return $item !== null;
    //     });
    //     // dd($payrollData);

    //     // Tampilkan view dengan data payroll yang sudah dihitung
    //     return view('superadmin.payroll.index', compact('payrollData'));
    // }



    public function index()
    {

        $employees = Employee::where('status', 'Active')->get();

        // Ambil pengaturan hari kerja efektif per bulan dari tabel WorkdaySetting
        $workdaySetting = WorkdaySetting::first(); // Ambil pengaturan pertama, jika ada

        if (!$workdaySetting) {
            return redirect()->route('settings.index')->with('error', 'Workday settings not found.');
        }

        // Ambil jumlah hari kerja efektif per bulan berdasarkan pengaturan
        // $monthlyWorkdays = $workdaySetting->monthly_workdays; 
        $salaryDeduction = SalaryDeduction::first();
        $lateDeduction = $salaryDeduction ? $salaryDeduction->late_deduction : 0;
        $earlyDeduction = $salaryDeduction ? $salaryDeduction->early_deduction : 0;


        // Proses data payroll untuk setiap karyawan
        $payrolls = $employees->map(function ($employee) use ($lateDeduction, $earlyDeduction) {

            // Ambil gaji pokok dari tabel employees
            $salary = $employee->current_salary;


            // Ambil bulan dan tahun yang digunakan untuk penghitungan payroll (misalnya bulan ini)
            $month = now()->format('Y-m');

            // Ambil data recap untuk karyawan dan bulan tertentu
            $recap = AttandanceRecap::where('employee_id', $employee->employee_id)
                ->where('month', $month)
                ->first();

            // Jika recap tidak ditemukan, defaultkan ke 0
            $totalDaysWorked = $recap ? $recap->total_present : 0;
            $totalLateCheckIn = $recap ? $recap->total_late : 0;
            $totalEarlyCheckOut = $recap ? $recap->total_early : 0;

            // Hitung total days off dari tabel offrequest
            // Hitung total days off dari tabel offrequest berdasarkan jenis cuti dan status approved
            $totalDaysOff = Offrequest::where('user_id', $employee->user_id)
                ->where('status', 'approved')
                ->whereYear('start_event', Carbon::parse($month)->year)
                ->whereMonth('start_event', Carbon::parse($month)->month)
                ->get()
                ->sum(function ($off) {
                    // Menghitung durasi antara start_event dan end_event
                    $start = Carbon::parse($off->start_event);
                    $end = Carbon::parse($off->end_event);
                    return $start->diffInDays($end) + 1; // Tambahkan 1 hari untuk inklusi
                });


            //menghitung effektive hari kerja
            $workdaySetting = WorkdaySetting::first();
            $monthlyWorkdays = $workdaySetting ? $workdaySetting->monthly_workdays : 0;
            $effectiveWorkDays = $monthlyWorkdays;

            $workDurationInHours = ($employee->check_in_time && $employee->check_out_time)
                ? Carbon::parse($employee->check_in_time)->diffInHours(Carbon::parse($employee->check_out_time))
                : 8; // Default 8 jam

            // Hitung gaji per hari dan per jam
            $dailySalary = $monthlyWorkdays > 0 ? $employee->current_salary / $monthlyWorkdays : 0;
            $hourlyRate = $workDurationInHours > 0 ? $dailySalary / $workDurationInHours : 0;

            // Ambil data overtime yang disetujui
            $overtimeData = Overtime::where('employee_id', $employee->employee_id)
                ->where('status', 'approved')
                ->get();
            $totalOvertimeHours = $overtimeData->sum('duration');

            // Hitung overtime pay
            $overtimePay = $totalOvertimeHours * $hourlyRate;

            // Debug jika nilai overtime pay masih salah
            if ($overtimePay === 0) {
                Log::info("Overtime pay is zero for employee ID: {$employee->employee_id}. Check hourlyRate: {$hourlyRate}, totalOvertimeHours: {$totalOvertimeHours}");
            }


            // Hitung total deduksi berdasarkan terlambat dan awal pulang
            $totalLateDeduction = $totalLateCheckIn * $lateDeduction;
            $totalEarlyDeduction = $totalEarlyCheckOut * $earlyDeduction;
            $totalDeductions = $totalLateDeduction + $totalEarlyDeduction;


            // Hitung total payroll
            $baseSalary = $totalDaysWorked * $dailySalary;
            $totalSalary = $baseSalary - $totalDeductions + $overtimePay;


            // Masukkan data payroll ke database
            Payroll::create([
                'employee_id' => $employee->employee_id,
                'current_salary' => $salary,
                'total_days_worked' => $totalDaysWorked,
                'total_days_off' => $totalDaysOff,
                'total_late_check_in' => $totalLateCheckIn,
                'total_early_check_out' => $totalEarlyCheckOut,
                'effective_work_days' => $effectiveWorkDays,
                'overtime_pay' => $overtimePay,
                'total_salary' => $totalSalary,
                'status' => 'Pending', // Status validasi
            ]);

            return [
                'id' => $employee->employee_id, 
                'employee_name' => $employee->first_name . ' ' . $employee->last_name,
                'current_salary' => $salary,
                'total_days_worked' => $totalDaysWorked,
                'total_days_off' => $totalDaysOff,
                'total_late_check_in' => $totalLateCheckIn,
                'total_early_check_out' => $totalEarlyCheckOut,
                'effective_work_days' => $effectiveWorkDays,
                'overtime_pay' => $overtimePay,
                'total_salary' => $totalSalary,
                'status' => 'Pending', // Contoh status validasi
            ];
        });


        return view('Superadmin.payroll.index', compact('payrolls'));
    }



    public function approve($id)
    {
        // Mencari payroll berdasarkan ID
        $payroll = Payroll::find($id);
    
        // Mengecek apakah payroll ditemukan dan statusnya 'pending'
        if ($payroll && $payroll->status === 'pending') {
            // Mengubah status menjadi 'approved'
            $payroll->status = 'approved';
            $payroll->save(); // Menyimpan perubahan ke database
    
            // Mengirim pesan sukses setelah berhasil approve
            return redirect()->route('payroll.index')->with('success', 'Payroll data approved!');
        }
    
        // Jika tidak ditemukan atau sudah approve
        return redirect()->route('payroll.index')->with('error', 'Payroll data already approved or not found.');
    }
    
    
    

    public function exportToCsv()
    {
        // Ambil data payroll yang statusnya approved
        $payrolls = Payroll::where('status', 'approved')->get();
    
        // Menyiapkan header CSV
        $csvHeader = ['Employee Name', 'Current Salary', 'Total Days Worked', 'Total Days Off', 'Total Late Check In', 'Total Early Check Out', 'Effective Work Days', 'Overtime Pay', 'Total Salary', 'Status'];
    
        // Menyiapkan data untuk CSV
        $csvData = $payrolls->map(function ($payroll) {
            return [
                $payroll->employee_name,
                number_format($payroll->current_salary, 0, ',', '.'), 
                $payroll->total_days_worked,
                $payroll->total_days_off,
                $payroll->total_late_check_in,
                $payroll->total_early_check_out,
                $payroll->effective_work_days,
                number_format($payroll->overtime_pay, 0, ',', '.'), 
                number_format($payroll->total_salary, 0, ',', '.'), 
                $payroll->status,
            ];
        });
    
        // Menambahkan header pada data CSV
        $csvData->prepend($csvHeader);
    
        // Menghasilkan file CSV untuk diunduh
        $filename = 'payroll_approved_' . now()->format('Y-m-d_H-i-s') . '.csv';
    
        // Return response untuk mengunduh CSV
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
    
        // Menghasilkan file CSV dan mengunduhnya
        return Response::stream(
            function () use ($csvData) {
                $handle = fopen('php://output', 'w');
                
                // Menulis header CSV
                fputcsv($handle, $csvData->shift()); 
    
                // Menulis baris data payroll
                foreach ($csvData as $row) {
                    fputcsv($handle, $row); 
                }
    
                fclose($handle);
            },
            200,
            $headers
        );
    }
    
    




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