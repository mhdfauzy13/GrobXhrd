<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\CheckDailyAttendance::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Jadwalkan untuk dijalankan setiap hari
        $schedule->command('attendance:check-daily')->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
