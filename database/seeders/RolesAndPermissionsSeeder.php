<?php

namespace Database\Seeders;

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
            'dashboard.view',
            'dashboardemployee.view',

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
            'recruitment.index',
            'recruitment.create',
            'recruitment.edit',
            'recruitment.delete',

            // Permission terkait Attandance
            'attandance.index',
            'attandance.scanView',
            'attandance.scan',
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


        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'superadmin'], ['status' => 'enable']);
        $managerRole = Role::firstOrCreate(['name' => 'manager'], ['status' => 'enable']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee'], ['status' => 'enable']);

        $adminRole->givePermissionTo($permissions); // Superadmin mendapatkan semua permission

        $managerRole->givePermissionTo([
            'dashboardemployee.view',
            'user.index',
            'user.edit',
            'role.index',
            'employee.index',
            'payroll.index',

            'recruitment.index',
            'offrequest.index',
            'offrequest.approver',
        ]);
        $employeeRole->givePermissionTo(['dashboardemployee.view', 'attandance.scan', 'offrequest.create', 'attandance.scanView']);

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



        $employee = User::updateOrCreate(
            [
                'email' => 'bungadevtri@gmail.com',
            ],
            [
                'name' => 'Bunga Putri',
                'password' => Hash::make('password'),
            ]
        );
        // Assign role Manager ke user Bunga Putri
        $employee->assignRole($managerRole);
    }



}
