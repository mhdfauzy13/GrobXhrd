<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\WorkdaySetting;
use App\Models\AttandanceRecap;

class PayrollSeeder extends Seeder
{
    public function run()
    {
        // Ambil workday setting untuk bulan ini
        $workdaySetting = WorkdaySetting::first();
        $monthlyWorkdays = $workdaySetting ? $workdaySetting->monthly_workdays : 22; // Default 22 hari jika tidak ada setting

        // Ambil semua karyawan beserta data user mereka
        $employees = Employee::with('user')->get();

        foreach ($employees as $employee) {
            // Pastikan employee memiliki user terkait
            if (!$employee->user) {
                echo "Employee tidak memiliki user terkait untuk ID: " . $employee->employee_id . "\n";
                continue;
            }

            // Ambil nama dari user terkait
            $employeeName = $employee->user->name;

            // Ambil data rekap absensi untuk karyawan
            $attendanceRecap = AttandanceRecap::where('employee_id', $employee->employee_id)->first();
            $totalPresent = $attendanceRecap ? $attendanceRecap->total_present : 0;
            $totalAbsences = $attendanceRecap ? $attendanceRecap->total_absent : 0;

            // Hitung gaji berdasarkan kehadiran
            $salaryPerDay = $employee->current_salary / $monthlyWorkdays;
            $totalSalary = ($totalPresent * $salaryPerDay) - ($totalAbsences * $salaryPerDay);

            // Simpan data ke payroll
            Payroll::updateOrCreate(
                ['employee_id' => $employee->employee_id],
                [
                    'employee_name' => $employeeName,
                    'total_salary' => $totalSalary,
                    'is_validated' => false,
                ]
            );
        }
    }
}
