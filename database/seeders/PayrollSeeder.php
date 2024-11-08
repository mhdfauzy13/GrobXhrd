<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\AttandanceRecap;
use App\Models\Overtime;
use App\Models\WorkdaySetting;
use App\Models\OffRequest;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PayrollSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Loop through each employee and create payroll data
        $employees = Employee::all(); // Fetch all employees

        foreach ($employees as $employee) {
            // Get current year and month
            $year = Carbon::now()->format('Y');
            $month = Carbon::now()->format('m');
            $monthYear = $year . $month;  // Format as YYYYMM

            // Create attendance recap
            $attandanceRecap = AttandanceRecap::create([
                'employee_id' => $employee->id,
                'month' => $monthYear,  // Use the YYYYMM format for month
                'total_present' => $faker->numberBetween(15, 22), // Random days worked
                'total_late' => $faker->numberBetween(0, 5), // Random late days
                'total_early' => $faker->numberBetween(0, 5), // Random early leave days
            ]);

            // Create overtime records for employee (dummy data)
            $overtime = Overtime::create([
                'employee_id' => $employee->id,
                'month' => $monthYear,  // Use the YYYYMM format for month
                'duration' => $faker->numberBetween(1, 5), // Random overtime hours
            ]);

            // Workday settings (assuming there is one set of settings for all employees)
            $workdaySetting = WorkdaySetting::first(); // Get the first workday setting record

            // OffRequests (dummy data for 2 approved off requests)
            $offRequest1 = OffRequest::create([
                'employee_id' => $employee->id,
                'start_event' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'end_event' => Carbon::now()->subDays(9)->format('Y-m-d'),
                'status' => 'approved',
            ]);
            $offRequest2 = OffRequest::create([
                'employee_id' => $employee->id,
                'start_event' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'end_event' => Carbon::now()->subDays(4)->format('Y-m-d'),
                'status' => 'approved',
            ]);

            // Create payroll record for employee
            $payroll = Payroll::create([
                'employee_id' => $employee->id,
                'attandance_recap_id' => $attandanceRecap->id,
                'overtime_id' => $overtime->id,
                'workday_setting_id' => $workdaySetting->id,
            ]);

            // Logic to calculate payroll based on attendance recap, off requests, overtime, etc.
            $payroll->total_days_worked = $attandanceRecap->total_present;
            $payroll->total_days_off = $offRequest1->getApprovedOffDays() + $offRequest2->getApprovedOffDays();
            $payroll->total_late_check_in = $attandanceRecap->total_late;
            $payroll->total_early_check_out = $attandanceRecap->total_early;
            $payroll->effective_work_days = $workdaySetting->monthly_workdays;
            $payroll->current_salary = $employee->current_salary;

            // Calculate overtime pay
            $hourlyRate = $employee->current_salary / ($workdaySetting->monthly_workdays * 8); // Assuming 8 hours per day
            $overtimePay = $overtime->duration * $hourlyRate;
            $payroll->overtime_pay = $overtimePay;

            // Total salary calculation
            $payroll->total_salary = (($employee->current_salary / $workdaySetting->monthly_workdays) * $payroll->total_days_worked) + $overtimePay;

            $payroll->save();
        }
    }
}
