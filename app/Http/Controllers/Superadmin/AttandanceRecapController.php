<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Attandance;
use App\Models\AttandanceRecap;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// class AttandanceRecapController extends Controller
// {
//     public function __construct()
//     {
//         // Middleware untuk izin khusus 'attendance.recap'
//         $this->middleware('permission:attendance.recap')->only('index', 'calculateMonthlyRecap', 'updateDailyRecap', 'markAbsentForToday', 'markAbsentForToday');
//     }

//     public function index(Request $request, $employee_id)
//     {
//         $monthYear = $request->input('month', now()->format('Y-m')); // Ambil bulan dari request atau gunakan bulan ini sebagai default
//         [$year, $month] = explode('-', $monthYear); // Pisahkan bulan dan tahun

//         // Ambil data rekap absensi berdasarkan employee_id, bulan, dan tahun
//         $recaps = AttandanceRecap::where('employee_id', $employee_id)->whereMonth('recap_month', $month)->whereYear('recap_month', $year)->first(); // Ambil 1 data (tidak perlu pakai get karena hasilnya hanya satu)

//         return view('Superadmin.Employeedata.Attandance.recap', compact('recaps', 'month', 'year', 'employee_id'));
//     }

//     // Menghitung dan merekap absensi bulanan
//     public function calculateMonthlyRecap()
//     {
//         $employees = Employee::all(); // Ambil semua karyawan
//         $currentMonth = now()->format('Y-m'); // Bulan dan tahun saat ini

//         foreach ($employees as $employee) {
//             // Ambil data absensi karyawan pada bulan dan tahun ini
//             $attendances = Attandance::where('employee_id', $employee->employee_id)
//                 ->whereMonth('date', now()->month)
//                 ->whereYear('date', now()->year)
//                 ->get();

//             // Hitung total kehadiran, terlambat, pulang awal, dan absen
//             $totalPresent = $attendances->count();
//             $totalLate = $attendances->where('status', 'Late')->count();
//             $totalEarlyLeave = $attendances->where('status', 'Early')->count();
//             $totalAbsent = $attendances->where('status', 'Absent')->count();

//             // Simpan atau update data rekap absensi
//             AttandanceRecap::updateOrCreate(
//                 [
//                     'employee_id' => $employee->employee_id,
//                     'recap_month' => $currentMonth,
//                 ],
//                 [
//                     'total_present' => $totalPresent,
//                     'total_late' => $totalLate,
//                     'total_early' => $totalEarlyLeave,
//                     'total_absent' => $totalAbsent,
//                 ],
//             );
//         }
//     }

//     // Fungsi untuk memperbarui rekap harian saat absensi baru dibuat
//     public function updateDailyRecap($employee_id, $date)
//     {
//         // Ambil absensi karyawan pada hari ini
//         $attendance = Attandance::where('employee_id', $employee_id)->whereDate('date', $date)->first();

//         $currentMonth = Carbon::parse($date)->format('Y-m'); // Format bulan saat ini

//         // Jika absensi ada, cek statusnya dan update rekap bulanan
//         if ($attendance) {
//             $status = $attendance->status;

//             AttandanceRecap::updateOrCreate(
//                 [
//                     'employee_id' => $employee_id,
//                     'recap_month' => $currentMonth,
//                 ],
//                 [
//                     'total_present' => $status === 'Present' ? DB::raw('total_present + 1') : DB::raw('total_present'),
//                     'total_late' => $status === 'Late' ? DB::raw('total_late + 1') : DB::raw('total_late'),
//                     'total_early' => $status === 'Early' ? DB::raw('total_early + 1') : DB::raw('total_early'),
//                 ],
//             );
//         } else {
//             // Jika absensi tidak ada, maka dianggap absen
//             AttandanceRecap::updateOrCreate(
//                 [
//                     'employee_id' => $employee_id,
//                     'recap_month' => $currentMonth,
//                 ],
//                 [
//                     'total_absent' => DB::raw('total_absent + 1'),
//                 ],
//             );
//         }
//     }

//     // Fungsi untuk mencatat absensi harian yang tidak ada (absent)
//     public function markAbsentForToday()
//     {
//         $employees = Employee::all(); // Ambil semua karyawan
//         $today = now()->format('Y-m-d'); // Tanggal hari ini

//         foreach ($employees as $employee) {
//             $attendance = Attandance::where('employee_id', $employee->employee_id)
//                 ->whereDate('date', $today)
//                 ->first();

//             if (!$attendance) {
//                 // Jika tidak ada catatan absensi hari ini, dianggap absen
//                 $this->updateDailyRecap($employee->employee_id, $today);
//             }
//         }
//     }
// }
