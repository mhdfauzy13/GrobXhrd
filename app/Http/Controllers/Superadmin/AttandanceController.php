<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attandance;
use App\Models\AttandanceRecap;
use App\Models\Offrequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AttandanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:attandance.index')->only('index', 'recap');
        $this->middleware('permission:attandance.scanView')->only(['scanView']);
        $this->middleware('permission:attandance.scan')->only(['checkIn', 'checkOut']);
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
            return view('Employee.attandance.scan', ['onLeave' => true]);
        }

        // Ambil data absensi karyawan untuk hari ini
        $attendance = $employee->attendances()->whereDate('created_at', $today)->first();
        $hasCheckedIn = $attendance && $attendance->check_in;
        $hasCheckedOut = $attendance && $attendance->check_out;

        return view('Employee.attandance.scan', compact('hasCheckedIn', 'hasCheckedOut', 'attendance', 'onLeave'));
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
        $attendance = $employee->attendances()->whereDate('created_at', $today)->first();

        // Jika sudah check-in, tidak perlu check-in lagi
        if ($attendance && $attendance->check_in) {
            return response()->json(['success' => false, 'message' => 'You are already absent today']);
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
        $scheduledCheckInTime = Carbon::createFromFormat('H:i:s', $checkInTime, $currentTime->timezone)->setDate($currentTime->year, $currentTime->month, $currentTime->day);
        $isLate = $currentTime->greaterThan($scheduledCheckInTime);

        // Jika belum ada data attendance untuk hari ini, buat data baru
        if (!$attendance) {
            $attendance = new Attandance([
                'employee_id' => $employee->employee_id,
            ]);
        }

        // Update hanya check_in dan status IN/LATE
        $attendance->check_in = $currentTime;
        $attendance->check_in_status = $isLate ? 'LATE' : 'IN'; // Simpan status check-in terpisah
        $attendance->image = $imagePath;
        $attendance->save();

        return response()->json(['success' => true, 'message' => 'Anda telah berhasil check-in', 'attendance' => $attendance]);
    }

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
        $attendance = $employee->attendances()->whereDate('created_at', $today)->first();

        // Jika belum check-in atau sudah check-out, tampilkan pesan yang sesuai
        if (!$attendance || !$attendance->check_in) {
            return response()->json(['success' => false, 'message' => 'Anda belum check-in hari ini']);
        }
        if ($attendance->check_out) {
            return response()->json(['success' => false, 'message' => 'You are already absent today']);
        }

        // Cek apakah sudah mencapai waktu check-out yang dijadwalkan
        $currentTime = now();
        $checkOutTime = $employee->check_out_time; // Waktu check-out yang dijadwalkan
        $scheduledCheckOutTime = Carbon::createFromFormat('H:i:s', $checkOutTime, $currentTime->timezone)->setDate($currentTime->year, $currentTime->month, $currentTime->day);

        // Tentukan status 'EARLY' jika check-out sebelum waktu yang dijadwalkan
        $statusCheckOut = 'OUT';
        if ($currentTime->lessThan($scheduledCheckOutTime)) {
            $statusCheckOut = 'EARLY'; // Set status menjadi EARLY
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

        // Update check_out dan status OUT/EARLY
        $attendance->check_out = $currentTime;
        $attendance->check_out_status = $statusCheckOut; // Simpan status check-out terpisah
        $attendance->image = $imagePath;
        $attendance->save();

        return response()->json(['success' => true, 'message' => 'Anda telah berhasil check-out', 'attendance' => $attendance]);
    }

    public function recap($employee_id, Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $employee = Employee::find($employee_id);

        if (!$employee) {
            return redirect()->back()->with('error', 'Karyawan tidak ditemukan');
        }

        $attendances = Attandance::where('employee_id', $employee_id)
            ->whereYear('created_at', Carbon::parse($month)->year)
            ->whereMonth('created_at', Carbon::parse($month)->month)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPresent = 0;
        $totalLate = 0;
        $totalEarly = 0;
        $totalAbsent = 0;

        foreach ($attendances as $attendance) {
            if ($attendance->check_in && $attendance->check_out) {
                // Selalu tambah ke total present jika ada check-in dan check-out
                $totalPresent++;

                if ($attendance->check_in_status == 'IN' && $attendance->check_out_status == 'OUT') {
                    // Kehadiran normal
                }
                if ($attendance->check_in_status == 'LATE' && $attendance->check_out_status == 'OUT') {
                    // Terlambat saat check-in tapi pulang tepat waktu
                    $totalLate++;
                }
                if ($attendance->check_in_status == 'IN' && $attendance->check_out_status == 'EARLY') {
                    // Tepat waktu saat check-in tapi pulang lebih awal
                    $totalEarly++;
                }
                if ($attendance->check_in_status == 'LATE' && $attendance->check_out_status == 'EARLY') {
                    // Terlambat saat check-in dan pulang lebih awal
                    $totalLate++;
                    $totalEarly++;
                }
            } else {
                // Jika tidak melakukan check-in atau check-out
                $totalAbsent++;
            }
        }

        $totalAbsent = $attendances
            ->filter(function ($attendance) {
                return !$attendance->check_in && !$attendance->check_out;
            })
            ->count();

        AttandanceRecap::updateOrCreate(
            ['employee_id' => $employee_id, 'month' => $month],
            [
                'total_present' => $totalPresent,
                'total_late' => $totalLate,
                'total_early' => $totalEarly,
                'total_absent' => $totalAbsent,
            ],
        );

        return view('Superadmin.Employeedata.Attandance.recap', compact('employee', 'attendances', 'totalPresent', 'totalLate', 'totalEarly', 'totalAbsent', 'month'));
    }
}
