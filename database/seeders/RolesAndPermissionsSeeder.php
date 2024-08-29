<?php 

namespace Database\Seeders;

use Spatie\Permission\Models\Role; // Gunakan model Role dari Spatie
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

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

        // Membuat atau memperbarui role dengan status
        $roles = [
            'admin' => [
                'permissions' => [
                    'role.index', 'role.create', 'role.edit', 'role.delete',
                    'user.index', 'user.create', 'user.edit', 'user.delete'
                ],
                'status' => 'enable',
            ],
            'user' => [
                'permissions' => [
                    'user.index'
                ],
                'status' => 'enable',
            ],
            'manager' => [ // Tambahkan role manager
                'permissions' => [
                    'role.index', 'user.index' // Tambahkan permissions sesuai kebutuhan
                ],
                'status' => 'enable',
            ]
        ];

        foreach ($roles as $roleName => $data) {
            $role = Role::updateOrCreate(
                ['name' => $roleName],
                ['status' => $data['status']]
            );
            $role->syncPermissions($data['permissions']);
        }
    }
}
