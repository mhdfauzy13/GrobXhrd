<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\Attandance;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Ambil role yang sudah ada
        $adminRole = Role::where('name', 'superadmin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $employeeRole = Role::where('name', 'employee')->first();

        // Menambahkan Superadmin hanya ke tabel User
        $superadminUser = User::updateOrCreate([
            'email' => 'superadmin@gmail.com',
        ], [
            'name' => 'Superadmin',
            'password' => Hash::make('password'),
        ]);

        // Assign role Superadmin ke user
        if ($adminRole) {
            $superadminUser->assignRole($adminRole);
        }

        // Menambahkan Manager ke tabel Employee dan User
        $managerEmployee = Employee::create([
            'first_name' => 'Manager',
            'last_name' => 'Example',
            'email' => 'manager@gmail.com',
            'place_birth' => $faker->city,
            'date_birth' => $faker->date,
            'personal_no' => 'P-000002',
            'address' => $faker->address,
            'current_address' => $faker->address,
            'blood_type' => $faker->randomElement(['A', 'B', 'AB', 'O']),
            'blood_rhesus' => $faker->randomElement(['+', '-']),
            'phone_number' => $faker->phoneNumber,
            'hp_number' => $faker->phoneNumber,
            'marital_status' => $faker->randomElement(['Married', 'Single']),
            'last_education' => $faker->randomElement(['SD', 'SMP', 'SMA', 'SMK', 'D1', 'D2', 'D3', 'S1']),
            'degree' => $faker->randomElement(['SMA', 'S1']),
            'starting_date' => $faker->date,
            'interview_by' => $faker->name,
            'current_salary' => $faker->numberBetween(3000000, 15000000),
            'insurance' => $faker->boolean,
            'serious_illness' => 'None',
            'hereditary_disease' => 'None',
            'emergency_contact' => $faker->name,
            'relations' => $faker->randomElement(['Spouse', 'Parent']),
            'emergency_number' => $faker->phoneNumber,
            'status' => 'active',
        ]);

        // Membuat pengguna Manager di tabel User
        $managerUser = User::updateOrCreate([
            'email' => 'manager@gmail.com',
        ], [
            'name' => 'Manager Example',
            'password' => Hash::make('password'),
        ]);

        // Assign role Manager ke user
        if ($managerRole) {
            $managerUser->assignRole($managerRole);
        }

        // Buat 15 dummy data karyawan
        for ($i = 1; $i <= 15; $i++) {
            // Membuat karyawan di tabel Employee
            $employee = Employee::create([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'place_birth' => $faker->city,
                'date_birth' => $faker->date,
                'personal_no' => $faker->unique()->numerify('P-######'),
                'address' => $faker->address,
                'current_address' => $faker->address,
                'blood_type' => $faker->randomElement(['A', 'B', 'AB', 'O']),
                'blood_rhesus' => $faker->randomElement(['+', '-']),
                'phone_number' => $faker->phoneNumber,
                'hp_number' => $faker->phoneNumber,
                'marital_status' => $faker->randomElement(['Married', 'Single', 'widow', 'widower']),
                'last_education' => $faker->randomElement(['SD', 'SMP', 'SMA', 'SMK', 'D1', 'D2', 'D3', 'S1']),
                'degree' => $faker->randomElement(['SMA', 'S1', 'S2']),
                'starting_date' => $faker->date,
                'interview_by' => $faker->name,
                'current_salary' => $faker->numberBetween(3000000, 15000000),
                'insurance' => $faker->boolean,
                'serious_illness' => $faker->randomElement(['None', 'Diabetes', 'Hypertension']),
                'hereditary_disease' => $faker->randomElement(['None', 'Heart Disease']),
                'emergency_contact' => $faker->name,
                'relations' => $faker->randomElement(['Spouse', 'Parent']),
                'emergency_number' => $faker->phoneNumber,
                'status' => 'active',
            ]);

            // Membuat pengguna untuk karyawan ini di tabel User
            $user = User::updateOrCreate([
                'email' => $employee->email,
            ], [
                'name' => $employee->first_name . ' ' . $employee->last_name,
                'password' => Hash::make('password'), // Password default
            ]);

            // Assign role Employee ke user
            if ($employeeRole) {
                $user->assignRole($employeeRole);
            }

            // Tambah data absensi untuk karyawan ini
            for ($j = 1; $j <= 5; $j++) { // 5 hari absensi dummy per karyawan
                $checkIn = Carbon::now()->subDays(rand(1, 30))->setTime(rand(7, 9), rand(0, 59));
                $checkOut = (clone $checkIn)->addHours(rand(8, 10)); // Waktu checkout acak antara 8-10 jam setelah checkin

                Attandance::create([
                    'employee_id' => $employee->employee_id, // Menggunakan id dari model Employee
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'check_in_status' => $faker->randomElement(['IN', 'LATE']),
                    'check_out_status' => $faker->randomElement(['OUT', 'EARLY']),
                    'image' => $faker->imageUrl(640, 480, 'people'),
                ]);
            }
        }
    }
}
