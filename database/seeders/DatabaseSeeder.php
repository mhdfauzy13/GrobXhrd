<?php

namespace Database\Seeders;

use App\Models\User;
use AttandanceSeeder;
use AttendanceRecapSeeder;
use AttendanceSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UserSeeder::class);
        $this->call([EventSeeder::class]);
        $this->call(PayrollSeeder::class);

        $this->call(AttendanceSeeder::class);

    }
}
