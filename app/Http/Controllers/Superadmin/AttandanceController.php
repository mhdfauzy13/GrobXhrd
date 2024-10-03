<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attandance;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AttandanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:attandance.index')->only('index');
        $this->middleware('permission:attandance.scanView')->only(['scanView']);
        $this->middleware('permission:attandance.scan')->only(['scan','getAttendanceState']);
    }

    public function index(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $attendances = Attandance::with('employee')->whereDate('created_at', $date)->orderBy('created_at', 'desc')->get();

        return view('Superadmin.Employeedata.Attandance.index', compact('attendances', 'date'));
    }

    public function scanView()
    {
        $employee = Employee::where('user_id', Auth::id())->first();
        $today = now()->format('Y-m-d');

        // Ambil data absensi karyawan untuk hari ini dari database
        $attendance = $employee->attendances()->whereDate('created_at', $today)->first();

        // Tentukan apakah karyawan sudah check-in dan check-out
        $checkIn = !$attendance || ($attendance && !$attendance->check_out);

        // Kirim status check-in/check-out dan data absensi ke view
        return view('Employee.attandance.scan', compact('checkIn', 'attendance'));
    }

    public function scan(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
            'check_in' => 'required|boolean', // True untuk check-in, False untuk check-out
        ]);

        // Cari employee berdasarkan Auth user
        $employee = Employee::where('user_id', Auth::id())->first();
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found']);
        }

        // Cek apakah sudah ada data absensi untuk hari ini
        $today = now()->format('Y-m-d');
        $attendance = $employee->attendances()->whereDate('created_at', $today)->first();

        // Cek waktu check-in dan check-out dari employee
        $checkInTime = $employee->check_in_time; // Contoh: '09:00:00'
        $checkOutTime = $employee->check_out_time; // Contoh: '17:00:00'
        $currentTime = now(); // Waktu saat ini
        $imagePath = null;

        // Handle upload gambar
        if ($request->has('image')) {
            $imageData = $request->input('image');
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageName = uniqid() . '.jpg';
            Storage::put('public/attandance_images/' . $imageName, base64_decode($imageData));
            $imagePath = 'attandance_images/' . $imageName;
        }

        // Cek apakah ini request check-in
        if ($request->input('check_in') === true) {
            if (!$attendance || ($attendance && $attendance->check_out)) {
                // Logika untuk check-in
                $scheduledCheckInTime = Carbon::createFromFormat('H:i:s', $checkInTime, $currentTime->timezone)->setDate($currentTime->year, $currentTime->month, $currentTime->day);
                $isLate = $currentTime->greaterThan($scheduledCheckInTime);

                // Simpan data check-in
                $newAttendance = Attandance::create([
                    'employee_id' => $employee->employee_id,
                    'check_in' => $currentTime,
                    'status' => $isLate ? 'LATE' : 'IN',
                    'image' => $imagePath,
                ]);

                return response()->json(['success' => true, 'message' => 'Anda telah berhasil check-in', 'attendance' => $newAttendance]);
            } else {
                return response()->json(['success' => false, 'message' => 'Sudah check-in, silakan check-out terlebih dahulu']);
            }
        }

        // Cek apakah ini request check-out
        if ($request->input('check_in') === false) {
            if ($attendance && !$attendance->check_out) {
                $scheduledCheckOutTime = Carbon::createFromFormat('H:i:s', $checkOutTime, $currentTime->timezone)->setDate($currentTime->year, $currentTime->month, $currentTime->day);

                if ($currentTime->lessThan($scheduledCheckOutTime)) {
                    return response()->json(['success' => false, 'message' => 'Tidak dapat check-out sebelum waktu yang dijadwalkan']);
                } else {
                    // Simpan data check-out
                    $attendance->check_out = $currentTime; // Update check_out
                    $attendance->status = 'OUT'; // Update status
                    $attendance->image = $imagePath; // Optional: Update image
                    $attendance->save(); // Simpan perubahan

                    return response()->json(['success' => true, 'message' => 'Anda telah berhasil check-out', 'attendance' => $attendance]);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Absen telah terpenuhi untuk hari ini ']);
            }
        }
    }

    // Tambahkan metode baru untuk mendapatkan status kehadiran
    public function getAttendanceState()
    {
        // Cari employee berdasarkan Auth user
        $employee = Employee::where('user_id', Auth::id())->first();
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found']);
        }

        // Periksa apakah karyawan sudah check-in atau check-out hari ini
        $today = now()->format('Y-m-d');
        $attendance = $employee->attendances()->whereDate('created_at', $today)->first();
        $checkInStatus = !$attendance || ($attendance && !$attendance->check_out);

        return response()->json(['success' => true, 'isCheckIn' => $checkInStatus]);
    }
}
