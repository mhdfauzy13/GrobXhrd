<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Ambil role yang sudah ada
        $adminRole = Role::where('name', 'Superadmin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $employeeRole = Role::where('name', 'employee')->first();

        // Menambahkan user Superadmin
        $superadmin = User::updateOrCreate([
            'email' => 'superadmin@gmail.com',
        ], [
            'name' => 'Superadmin',
            'password' => Hash::make('password'), // Hash password di sini
        ]);

        // Assign role Superadmin ke user
        if ($adminRole) {
            $superadmin->assignRole($adminRole); // Tidak perlu menggunakan role_id
        }

        // Menambahkan user Manager
        $manager = User::updateOrCreate([
            'email' => 'manager@gmail.com',
        ], [
            'name' => 'Manager',
            'password' => Hash::make('password'),
        ]);

        // Assign role Manager ke user
        if ($managerRole) {
            $manager->assignRole($managerRole); // Menggunakan assignRole untuk role manager
        }

        // Menambahkan user Employee
        $employee = User::updateOrCreate([
            'email' => 'employee@gmail.com',
        ], [
            'name' => 'Employee',
            'password' => Hash::make('password'),
        ]);

        // Assign role Employee ke user
        if ($employeeRole) {
            $employee->assignRole($employeeRole); // Assign role employee ke user
        }
    }
}
