<?php
// database/seeders/WorkdaySettingsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkdaySetting;

class WorkdaySettingsSeeder extends Seeder
{
    public function run()
    {
        // Tentukan hari kerja efektif default
        $effectiveDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        
        // Hitung jumlah hari kerja efektif per bulan (asumsi 4 minggu per bulan)
        $weeklyWorkdays = count($effectiveDays);
        $monthlyWorkdays = $weeklyWorkdays * 4;

        // Buat data default di tabel workday_settings
        WorkdaySetting::create([
            'effective_days' => $effectiveDays,
            'monthly_workdays' => $monthlyWorkdays,
        ]);
    }
}
