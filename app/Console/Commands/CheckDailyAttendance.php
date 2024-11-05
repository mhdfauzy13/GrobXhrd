<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\Attandance;
use App\Models\AttandanceRecap;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CheckDailyAttendance extends Command
{
    protected $signature = 'attendance:check-daily';
    protected $description = 'Check daily attendance and increment total_absent if no check-in or check-out is found';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = Carbon::today();
        $employees = Employee::all();

        foreach ($employees as $employee) {
            // Cek apakah ada check-in atau check-out untuk employee hari ini
            $attendanceToday = Attandance::where('employee_id', $employee->employee_id)
                ->whereDate('created_at', $today)
                ->first();

            if (!$attendanceToday) {
                // Jika tidak ada, tambahkan absen ke recap
                $month = $today->format('Y-m');
                $recap = AttandanceRecap::updateOrCreate(
                    ['employee_id' => $employee->employee_id, 'month' => $month],
                    ['total_absent' => DB::raw('total_absent + 1')]
                );

                $this->info("Employee ID {$employee->employee_id} marked absent for {$today->toDateString()}");
            }
        }

        $this->info('Daily attendance check completed.');
    }
}
