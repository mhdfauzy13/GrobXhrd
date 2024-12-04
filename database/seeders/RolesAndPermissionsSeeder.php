<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Daftar semua permissions
        $permissions = [
            //Permission dashboard
            'dashboard.superadmin',
            'dashboard.employee',

            // Permission terkait Role
            'role.index',
            'role.create',
            'role.edit',
            'role.delete',

            // Permission terkait User
            'user.index',
            'user.create',
            'user.edit',
            'user.delete',

            // Permission terkait Company
            'company.index',
            'company.create',
            'company.edit',
            'company.delete',

            // Permission terkait Employee
            'employee.index',
            'employee.create',
            'employee.edit',
            'employee.delete',
            'employee.show',

            // Permission terkait Payroll
            'payroll.index',
            'payroll.create',
            'payroll.edit',
            'payroll.delete',

            // Permission terkait Recruitment
            // Permission terkait Recruitment
            'recruitment.index',
            'recruitment.create',
            'recruitment.edit',
            'recruitment.delete',
            'recruitment.show',

            // Permission terkait Attendance
            'attendance.index',
            'attendance.scan',
            'attendance.recap',

            // Permission terkait Offrequest
            'offrequest.index',
            'offrequest.create',
            'offrequest.approver',

            //event
            'event.index',
            'event.lists',
            'event.create',
            'event.edit',
            'event.delete',

            // employeebooks
            'employeebook.index',
            'employeebook.create',
            'employeebook.edit',
            'employeebook.delete',
            'employeebook.detail',

            // setting
            'settings.index',
            'settings.company',
            'settings.deductions',
            'settings.worksdays',

            //overtime
            'overtime.create',
            'overtime.approvals',

            //payroll
            'payroll.index',

            //Division
            'divisions.index',
            'divisions.create',
            'divisions.edit',
            'divisions.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'superadmin'], ['status' => 'enable']);
        $managerRole = Role::firstOrCreate(['name' => 'manager'], ['status' => 'enable']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee'], ['status' => 'enable']);

        $adminRole->givePermissionTo($permissions); // Superadmin mendapatkan semua permission
        $managerRole->givePermissionTo(['dashboard.superadmin', 'user.index', 'user.edit', 'role.index', 'employee.index', 'payroll.index', 'recruitment.index', 'offrequest.index', 'offrequest.approver','overtime.approvals','overtime.create']);
        $employeeRole->givePermissionTo(['dashboard.employee', 'attendance.scan', 'offrequest.index', 'offrequest.create', 'attendance.scan']);


        $superadmin = User::updateOrCreate(
            [
                'email' => 'superadmin@gmail.com',
            ],
            [
                'name' => 'Superadmin',
                'password' => Hash::make('password'),
            ],
        );
        $superadmin->assignRole($adminRole);

        $manager = User::updateOrCreate(
            [
                'email' => 'manager@gmail.com',
            ],
            [
                'name' => 'Manager',
                'password' => Hash::make('password'),
            ],
        );
        $manager->assignRole($managerRole);

        $employee = User::updateOrCreate(
            [
                'email' => 'employee@gmail.com',
            ],
            [
                'name' => 'Employee',
                'password' => Hash::make('password'),
            ],
        );
        $employee->assignRole($employeeRole);

    // Pertama, pastikan employee Bunga Putri sudah ada di tabel Employee
$bungadevtriEmployee = Employee::firstOrCreate(
    [
        'email' => 'bungadevtri@gmail.com',
    ],
    [
        'first_name' => 'Bunga',
        'last_name' => 'Putri',
        'check_in_time' => '10.00',
        'check_out_time' => '17.00',
        'place_birth' => 'City',
        'date_birth' => now(),
        'identity_number' => 'P-000003',
        'address' => 'Some Address',
        'current_address' => 'Some Address',
        'blood_type' => 'O',
        'blood_rhesus' => '+',
        'phone_number' => '1234567890',
        'hp_number' => '0987654321',
        'marital_status' => 'Single',
        'cv_file' => 'default_cv.pdf',
        'last_education' => 'Elementary School',
        'degree' => 'S.Kom',
        'starting_date' => now(),
        'interview_by' => 'Interviewer',
        'current_salary' => 5000000,
        'insurance' => true,
        'serious_illness' => 'None',
        'hereditary_disease' => 'None',
        'emergency_contact' => 'Mother',
        'relations' => 'Parent',
        'emergency_number' => '1234567890',
        'status' => 'Active',
    ],
);

// Membuat pengguna untuk Bunga Putri
$bungadevtri = User::updateOrCreate(
    [
        'email' => 'bungadevtri@gmail.com',
    ],
    [
        'name' => 'Bunga Putri',
        'password' => Hash::make('password'),
        'employee_id' => $bungadevtriEmployee->employee_id, // Relasi dengan data Employee
    ]
);

// Assign role Manager ke Bunga Putri
$bungadevtri->assignRole($managerRole);

}
}
