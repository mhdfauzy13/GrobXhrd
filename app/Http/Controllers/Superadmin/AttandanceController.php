<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attandance;
use App\Models\AttandanceRecap;
use App\Models\Event;
use App\Models\Offrequest;
use App\Models\WorkdaySetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AttandanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:attendance.index')->only('index', 'recap');
        $this->middleware('permission:attendance.scan')->only(['scanView', 'checkIn', 'checkOut']);
    }

    public function index(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $attendances = Attandance::with('employee')->whereDate('created_at', $date)->orderBy('created_at', 'desc')->paginate(15); // Hapus duplikasi query

        return view('Superadmin.Employeedata.Attandance.index', compact('attendances', 'date'));
    }

    public function scanView()
    {
        $employee = Employee::where('user_id', Auth::id())->first();
        $today = now()->format('Y-m-d');

        // Periksa apakah karyawan sedang cuti untuk hari ini
        $onLeave = Offrequest::where('user_id', Auth::id())->where('status', 'approved')->whereDate('start_event', '<=', $today)->whereDate('end_event', '>=', $today)->exists();

        if ($onLeave) {
            // Jika karyawan sedang cuti, langsung kirimkan status cuti
            return view('Employee.attandance.scan', ['onLeave' => true, 'employee' => $employee]);
        }

        // Ambil data absensi karyawan untuk hari ini
        $attendance = $employee->attendances()->whereDate('created_at', $today)->first();
        $hasCheckedIn = $attendance && $attendance->check_in;
        $hasCheckedOut = $attendance && $attendance->check_out;

        return view('Employee.attandance.scan', compact('hasCheckedIn', 'hasCheckedOut', 'attendance', 'onLeave', 'employee'));
    }

    // Fungsi untuk Check-in
    public function checkIn(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
        ]);

        $employee = Employee::where('user_id', Auth::id())->first();
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found']);
        }

        // Periksa apakah karyawan sedang cuti
        $today = now()->format('Y-m-d');
        $onLeave = Offrequest::where('user_id', Auth::id())->where('status', 'approved')->whereDate('start_event', '<=', $today)->whereDate('end_event', '>=', $today)->exists();

        if ($onLeave) {
            return response()->json(['success' => false, 'message' => 'You are on leave today']);
        }

        // Ambil data absensi karyawan untuk hari ini
        $attendance = $employee->attendances()->whereDate('created_at', $today)->first();

        // Jika sudah check-in, tidak perlu check-in lagi
        if ($attendance && $attendance->check_in) {
            return response()->json(['success' => false, 'message' => 'You have already checked in today']);
        }

        // Simpan gambar yang diupload
        $imagePath = null;
        if ($request->has('image')) {
            $imageData = $request->input('image');
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageName = uniqid() . '.jpg';
            Storage::put('public/attandance_images/' . $imageName, base64_decode($imageData));
            $imagePath = 'attandance_images/' . $imageName;
        }

        // Waktu sekarang dan waktu check-in yang dijadwalkan
        $currentTime = now();
        $checkInTime = $employee->check_in_time; // Waktu check-in yang dijadwalkan
        $toleranceMinutes = 1; // Toleransi waktu dalam menit

        $scheduledCheckInTime = Carbon::createFromFormat('H:i:s', $checkInTime, $currentTime->timezone)->setDate($currentTime->year, $currentTime->month, $currentTime->day);
        $latestAllowedCheckInTime = $scheduledCheckInTime->copy()->addMinutes($toleranceMinutes);

        // Tentukan status 'LATE' jika check-in melewati toleransi
        $isLate = $currentTime->greaterThan($latestAllowedCheckInTime);

        // Jika belum ada data attendance untuk hari ini, buat data baru
        if (!$attendance) {
            $attendance = new Attandance([
                'employee_id' => $employee->employee_id,
            ]);
        }

        // Update hanya check_in dan status IN/LATE
        $attendance->check_in = $currentTime;
        $attendance->check_in_status = $isLate ? 'LATE' : 'IN';
        $attendance->image = $imagePath;
        $attendance->save();

        $message = $isLate ? 'Check-in successful, but you are late.' : 'Check-in successful, on time.';
        return response()->json(['success' => true, 'message' => $message, 'attendance' => $attendance]);
    }

    // Fungsi untuk Check-out

    // Fungsi untuk Check-out
    public function checkOut(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
        ]);

        $employee = Employee::where('user_id', Auth::id())->first();
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found']);
        }

        // Periksa apakah karyawan sedang cuti
        $today = now()->format('Y-m-d');
        $onLeave = Offrequest::where('user_id', Auth::id())->where('status', 'approved')->whereDate('start_event', '<=', $today)->whereDate('end_event', '>=', $today)->exists();

        if ($onLeave) {
            return response()->json(['success' => false, 'message' => 'You are on leave today']);
        }

        // Ambil data absensi karyawan untuk hari ini
        $attendance = $employee->attendances()->whereDate('created_at', $today)->first();

        if (!$attendance || !$attendance->check_in) {
            return response()->json(['success' => false, 'message' => 'You have not checked in today']);
        }

        if ($attendance->check_out) {
            return response()->json(['success' => false, 'message' => 'You have already checked out today']);
        }

        // Cek apakah sudah mencapai waktu check-out yang dijadwalkan
        $currentTime = now();
        $checkOutTime = $employee->check_out_time; // Waktu check-out yang dijadwalkan
        $toleranceMinutes = 1; // Toleransi waktu dalam menit

        $scheduledCheckOutTime = Carbon::createFromFormat('H:i:s', $checkOutTime, $currentTime->timezone)->setDate($currentTime->year, $currentTime->month, $currentTime->day);
        $earliestAllowedCheckOutTime = $scheduledCheckOutTime->copy()->subMinutes($toleranceMinutes);

        // Status check-out 'EARLY' atau 'OUT'
        $isEarly = $currentTime->lessThan($earliestAllowedCheckOutTime);
        $statusCheckOut = $isEarly ? 'EARLY' : 'OUT';

        // Jika check-out early, konfirmasi pengguna
        if ($isEarly && !$request->has('confirmedEarly')) {
            return response()->json([
                'success' => true,
                'message' => 'You are checking out early. Do you want to proceed?',
                'early' => true,
            ]);
        }

        // Simpan gambar yang diupload
        $imagePath = null;
        if ($request->has('image')) {
            $imageData = $request->input('image');
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageName = uniqid() . '.jpg';
            Storage::put('public/attandance_images/' . $imageName, base64_decode($imageData));
            $imagePath = 'attandance_images/' . $imageName;
        }

        // Update check_out dan status
        $attendance->check_out = $currentTime;
        $attendance->check_out_status = $statusCheckOut;
        $attendance->image = $imagePath;
        $attendance->save();

        $message = $statusCheckOut === 'EARLY' ? 'Check-out successful, but you left early.' : 'Check-out successful, on time.';

        return response()->json([
            'success' => true,
            'message' => $message,
            'attendance' => $attendance,
        ]);
    }

    public function recap($employee_id, Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $employee = Employee::find($employee_id);

        if (!$employee) {
            return redirect()->back()->with('error', 'Karyawan tidak ditemukan');
        }

        // Ambil pengaturan hari kerja
        $workdaySetting = WorkdaySetting::first();
        if (!$workdaySetting) {
            return redirect()->back()->with('error', 'Pengaturan hari kerja belum diatur.');
        }
        $workdays = $workdaySetting->effective_days;

        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::now();
        $holidays = [];

        $dangerEvents = Event::whereBetween('start_date', [$startDate, $endDate])
            ->where('category', 'danger')
            ->get();

        foreach ($dangerEvents as $event) {
            $rangeStart = Carbon::parse($event->start_date);
            $rangeEnd = Carbon::parse($event->end_date);

            while ($rangeStart <= $rangeEnd) {
                $holidays[] = $rangeStart->toDateString();
                $rangeStart->addDay();
            }
        }

        // Hitung total hari kerja efektif
        $totalEffectiveWorkdays = 0;
        for ($date = $startDate; $date <= $endDate; $date->addDay()) {
            if (in_array($date->format('l'), $workdays) && !in_array($date->toDateString(), $holidays)) {
                $totalEffectiveWorkdays++;
            }
        }

        // Ambil data kehadiran
        $attendances = Attandance::where('employee_id', $employee_id)
            ->whereYear('created_at', Carbon::parse($month)->year)
            ->whereMonth('created_at', Carbon::parse($month)->month)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPresent = 0;
        $totalLate = 0;
        $totalEarly = 0;

        foreach ($attendances as $attendance) {
            if ($attendance->check_in && $attendance->check_out) {
                $totalPresent++;

                if ($attendance->check_in_status == 'LATE') {
                    $totalLate++;
                }
                if ($attendance->check_out_status == 'EARLY') {
                    $totalEarly++;
                }
            }
        }

        // Hitung total absent berdasarkan total hari kerja efektif
        $totalAbsent = $totalEffectiveWorkdays - $totalPresent;

        // Simpan ke recap
        AttandanceRecap::updateOrCreate(
            ['employee_id' => $employee_id, 'month' => $month],
            [
                'total_present' => $totalPresent,
                'total_late' => $totalLate,
                'total_early' => $totalEarly,
                'total_absent' => max(0, $totalAbsent),
            ],
        );

        return view('Superadmin.Employeedata.Attandance.recap', compact('employee', 'attendances', 'totalPresent', 'totalLate', 'totalEarly', 'totalAbsent', 'month'));
    }
}
