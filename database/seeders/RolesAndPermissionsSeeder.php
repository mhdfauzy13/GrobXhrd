<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Buat permissions
        $permissions = [
            'role.index', 'role.create', 'role.edit', 'role.delete',
            'user.index', 'user.create', 'user.edit', 'user.delete',
        
        ];        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Buat roles dan tambahkan permissions
        // $adminRole = Role::firstOrCreate(['name' => 'admin', 'status' => true]);
        // $adminRole->syncPermissions($permissions);

        // $userRole = Role::firstOrCreate(['name' => 'user', 'status' => true]);
        // $userRole->syncPermissions(['read']);

                    
                    //ini yang baru nya
                    // $adminRole = Role::firstOrCreate(['name' => 'admin']);
                    // $adminRole->syncPermissions($permissions);

                    // $userRole = Role::firstOrCreate(['name' => 'user']);
                    // $userRole->syncPermissions(['user.create', 'user.edit', 'user.index']);

                    $roles = [
                        'Admin' => ['role.index', 'role.create', 'role.edit', 'role.delete', 'user.index', 'user.create', 'user.edit', 'user.delete'],
                        'User' => ['user.index']
                    ];
            
                    foreach ($roles as $roleName => $permissions) {
                        Role::updateOrCreate(
                            ['name' => $roleName],
                            ['status' => 'enable']
                        )->syncPermissions($permissions);
                    }
                
                }
}
