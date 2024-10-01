<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
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
            'employee.destroy',

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

            // Permission terkait Offrequest
            'offrequest.index',
            'offrequest.create',
            'offrequest.store',
            'offrequest.approver',
            'offrequest.reject',
        ];

        // Membuat atau memperbarui permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Buat roles
        $adminRole = Role::firstOrCreate(['name' => 'superadmin'], ['status' => 'enable']);
        $managerRole = Role::firstOrCreate(['name' => 'manager'], ['status' => 'enable']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee'], ['status' => 'enable']);

        // Assign permissions ke roles
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

        // Buat dan assign roles ke users
        // User Superadmin
        $superadmin = User::updateOrCreate(
            [
                'email' => 'superadmin@gmail.com',
            ],
            [
                'name' => 'Superadmin',
                'password' => Hash::make('password'),
            ],
        );
        $superadmin->assignRole($adminRole); // Assign role superadmin

        // User Manager
        $manager = User::updateOrCreate(
            [
                'email' => 'manager@gmail.com',
            ],
            [
                'name' => 'Manager',
                'password' => Hash::make('password'),
            ],
        );
        $manager->assignRole($managerRole); // Assign role manager

        // User Employee
        $employee = User::updateOrCreate(
            [
                'email' => 'employee@gmail.com',
            ],
            [
                'name' => 'Employee',
                'password' => Hash::make('password'),
            ],
        );
        $employee->assignRole($employeeRole); // Assign role employee
    }
}
