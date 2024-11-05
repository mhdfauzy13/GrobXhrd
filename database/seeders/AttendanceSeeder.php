<?php

namespace Database\Seeders;

use App\Models\Attandance;
use App\Models\AttandanceRecap;
use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\AttendanceRecap;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        // Mendapatkan semua ID karyawan
        $employeeIds = range(1, 10); // Sesuaikan dengan jumlah karyawan yang ada di tabel employees

        foreach ($employeeIds as $employeeId) {
            // Membuat data kehadiran untuk setiap hari dalam bulan ini
            for ($day = 1; $day <= 30; $day++) {
                $checkInTime = Carbon::create(2024, 11, $day, rand(8, 9), rand(0, 59)); // Jam check in acak antara jam 8 dan 9
                $checkOutTime = $checkInTime->copy()->addHours(8); // Jam check out 8 jam setelah check in

                // Membuat data attendance
                Attandance::create([
                    'employee_id' => $employeeId,
                    'check_in' => $checkInTime,
                    'check_out' => $checkOutTime,
                    'check_in_status' => 'IN',
                    'check_out_status' => 'OUT',
                    'image' => null, // Ganti dengan path gambar jika ada
                ]);
            }

            // Menghitung total kehadiran untuk bulan ini
            $totalPresent = 20; // Misalkan total hadir adalah 20
            $totalLate = rand(0, 5); // Total telat acak
            $totalEarly = rand(0, 5); // Total awal acak
            $totalAbsent = 30 - ($totalPresent + $totalLate + $totalEarly); // Total tidak hadir

            // Membuat data recap kehadiran bulanan
            AttandanceRecap::create([
                'employee_id' => $employeeId,
                'month' => Carbon::now()->format('Y-m'), // Bulan saat ini
                'total_present' => $totalPresent,
                'total_late' => $totalLate,
                'total_early' => $totalEarly,
                'total_absent' => $totalAbsent,
            ]);
        }
    }
}
