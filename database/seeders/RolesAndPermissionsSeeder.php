<?php 

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Menambahkan permissions jika belum ada
        $permissions = [
            'role.index', 'role.create', 'role.edit', 'role.delete',
            'user.index', 'user.create', 'user.edit', 'user.delete',
            // Tambahkan permission lainnya jika diperlukan
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Buat Roles dan Assign Permissions
        $adminRole = Role::firstOrCreate(['name' => 'superadmin'], ['status' => 'enable']);
        $managerRole = Role::updateOrCreate(['name' => 'manager'], ['status' => 'enable']);
        $employeeRole = Role::updateOrCreate(['name' => 'employee'], ['status' => 'enable']);

        // Berikan permission ke masing-masing role
        $managerRole->givePermissionTo(['user.index', 'user.edit', 'role.index']);
        $employeeRole->givePermissionTo(['user.index', 'user.edit']);

        // Menambahkan users dan meng-assign roles

        // User Superadmin
        $superadmin = User::updateOrCreate([
            'email' => 'superadmin@gmail.com',
        ], [
            'name' => 'Superadmin',
            'password' => Hash::make('password'),
        ]);
        $superadmin->assignRole($adminRole); // Assign role superadmin

        // User Manager
        $manager = User::updateOrCreate([
            'email' => 'manager@gmail.com',
        ], [
            'name' => 'Manager',
            'password' => Hash::make('password'),
        ]);
        $manager->assignRole($managerRole); // Assign role manager

        // User Employee
        $employee = User::updateOrCreate([
            'email' => 'employee@gmail.com',
        ], [
            'name' => 'Employee',
            'password' => Hash::make('password'),
        ]);
        $employee->assignRole($employeeRole); // Assign role employee
    }
}

