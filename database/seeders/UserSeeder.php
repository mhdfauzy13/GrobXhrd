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
        $adminRole = Role::where('name', 'superadmin')->first(); // Nama role sesuai di seeder sebelumnya
        $managerRole = Role::where('name', 'manager')->first();
        $employeeRole = Role::where('name', 'employee')->first();

        // Menambahkan user Superadmin
        $superadmin = User::updateOrCreate([
            'email' => 'superadmin@gmail.com',
        ], [
            'name' => 'Superadmin',
            'password' => Hash::make('password'),
        ]);

        // Assign role Superadmin ke user
        if ($adminRole) {
            $superadmin->assignRole($adminRole);
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
            $manager->assignRole($managerRole);
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
            $employee->assignRole($employeeRole);
        }

        // Menambahkan user bunga
        $employee = User::updateOrCreate([
            'email' => 'bungadevtri@gmail.com',
        ], [
            'name' => 'Bunga Putri',
            'password' => Hash::make('password'),
        ]);
        // Assign role Bunga ke user
        if ($managerRole) {
            $manager->assignRole($managerRole);
        }
    }
}
