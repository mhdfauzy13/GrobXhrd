<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\WorkdaySetting;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Ambil pengaturan hari kerja untuk semua employee
        $workdaySetting = WorkdaySetting::first();
        if (!$workdaySetting) {
            $this->command->info('Workday settings are missing. Seeder will continue, but ensure to add them later.');
        }

        // Ambil role yang sudah ada
        $adminRole = Role::where('name', 'superadmin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $employeeRole = Role::where('name', 'employee')->first();

        // Menambahkan Superadmin hanya ke tabel User
        $superadminUser = User::updateOrCreate(
            [
                'email' => 'superadmin@gmail.com',
            ],
            [
                'name' => 'Superadmin',
                'password' => Hash::make('password'),
                'employee_id' => null, // Superadmin tidak memiliki employee_id
            ]
        );

        // Assign role Superadmin ke user
        if ($adminRole) {
            $superadminUser->assignRole($adminRole);
        } else {
            $this->command->warn('Role "superadmin" not found.');
        }

        // Menambahkan Manager ke tabel Employee dan User
        $managerEmployee = Employee::firstOrCreate([
            'first_name' => 'Manager',
            'last_name' => 'Example',
            'email' => 'manager@gmail.com',
            'place_birth' => $faker->city,
            'date_birth' => $faker->date,
            'identity_number' => 'P-000002',
            'address' => $faker->address,
            'current_address' => $faker->address,
            'blood_type' => $faker->randomElement(['A', 'B', 'AB', 'O']),
            'blood_rhesus' => $faker->randomElement(['+', '-']),
            'phone_number' => $faker->phoneNumber,
            'hp_number' => $faker->phoneNumber,
            'marital_status' => $faker->randomElement(['Married', 'Single']),
            'last_education' => $faker->randomElement(['Elementary School', 'Junior High School', 'Senior High School']),
            'degree' => $faker->randomElement(['S.Kom', 'S.Ak']),
            'starting_date' => $faker->date,
            'interview_by' => $faker->name,
            'current_salary' => $faker->numberBetween(3000000, 15000000),
            'insurance' => $faker->boolean,
            'serious_illness' => 'None',
            'hereditary_disease' => 'None',
            'emergency_contact' => $faker->name,
            'relations' => $faker->randomElement(['Parent', 'Guardian', 'Husband', 'Wife', 'Sibling']),
            'emergency_number' => $faker->phoneNumber,
            'status' => 'Active',
        ]);

        // Membuat pengguna Manager di tabel User
        $managerUser = User::updateOrCreate(
            [
                'email' => 'manager@gmail.com',
            ],
            [
                'name' => 'Manager Example',
                'password' => Hash::make('password'),
                'employee_id' => $managerEmployee->employee_id, // Set employee_id dari Employee
            ]
        );

        // Assign role Manager ke user
        if ($managerRole) {
            $managerUser->assignRole($managerRole);
        } else {
            $this->command->warn('Role "manager" not found.');
        }

        // Buat 15 dummy data karyawan
        $employees = [];
        for ($i = 1; $i <= 15; $i++) {
            // Membuat karyawan di tabel Employee
            $employee = Employee::firstOrCreate([
                'email' => $faker->unique()->safeEmail,
            ], [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'place_birth' => $faker->city,
                'date_birth' => $faker->date,
                'identity_number' => $faker->unique()->numerify('P-######'),
                'address' => $faker->address,
                'current_address' => $faker->address,
                'blood_type' => $faker->randomElement(['A', 'B', 'AB', 'O']),
                'blood_rhesus' => $faker->randomElement(['+', '-']),
                'phone_number' => $faker->phoneNumber,
                'hp_number' => $faker->phoneNumber,
                'marital_status' => $faker->randomElement(['Married', 'Single', 'widow', 'widower']),
                'last_education' => $faker->randomElement(['Elementary School', 'Junior High School', 'Senior High School']),
                'degree' => $faker->randomElement(['S.Kom', 'S.Ak']),
                'starting_date' => $faker->date,
                'interview_by' => $faker->name,
                'current_salary' => $faker->numberBetween(3000000, 15000000),
                'insurance' => $faker->boolean,
                'serious_illness' => $faker->randomElement(['None', 'Diabetes', 'Hypertension']),
                'hereditary_disease' => $faker->randomElement(['None', 'Heart Disease']),
                'emergency_contact' => $faker->name,
                'relations' => $faker->randomElement(['Parent', 'Guardian', 'Husband', 'Wife', 'Sibling']),
                'emergency_number' => $faker->phoneNumber,
                'status' => 'Active',
                'check_in_time' => Carbon::now()->setTime(rand(7, 9), 0), // Set ke jam dengan menit 0
                'check_out_time' => Carbon::now()->setTime(rand(17, 19), 0), // Set ke jam dengan menit 0

            ]);

            // Membuat pengguna untuk karyawan ini di tabel User
            $user = User::updateOrCreate(
                [
                    'email' => $employee->email,
                ],
                [
                    'name' => $employee->first_name . ' ' . $employee->last_name,
                    'password' => Hash::make('password'), // Password default
                    'employee_id' => $employee->employee_id, // Set employee_id dari Employee
                ]
            );

            // Assign role Employee ke user
            if ($employeeRole) {
                $user->assignRole($employeeRole);
            } else {
                $this->command->warn('Role "employee" not found.');

            }


        }

        $this->command->info('Users and roles seeded successfully.');
    }
}
