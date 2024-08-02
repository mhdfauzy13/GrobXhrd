<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        // Ambil semua role beserta permissions yang terhubung
        $roles = Role::with('permissions')->get();

        // Misalnya, jika Anda ingin memisahkan antara role yang aktif dan yang dinonaktifkan
        $activeRoles = $roles->filter(function ($role) {
            return $role->status === 'enable';
        });

        $disabledRoles = $roles->filter(function ($role) {
            return $role->status === 'disable';
        });

        return view('superadmin.masterdata.role.index', compact('activeRoles', 'disabledRoles','roles'));

    }

    public function create()
    {
        // Ambil semua permissions untuk ditampilkan di form create
        // codingan awal
        $permissions = Permission::all();
        return view('superadmin.masterdata.role.create', compact('permissions'));



         

        return view('superadmin.masterdata.role.create', compact('groupedPermissions'));
    }

    public function store(Request $request)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name',
            'status' => 'required|string|in:enable,disable',
        ]);

        // Buat role baru
        $role = Role::create([
            'name' => $validatedData['name'],
            'status' => $validatedData['status'],
        ]);


        // Tambahkan permissions ke role
        // $role->syncPermissions($validatedData['permissions']);
        // if ($role->status == 'disable') {
            // Misalnya, beri tahu pengguna bahwa role ini dinonaktifkan
            // return redirect()->route('role.index')->with('warning', 'Role berhasil dibuat tetapi dinonaktifkan.');
        // }

        // Sync permissions
        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        return redirect()->route('role.index')->with('success', 'Role berhasil dibuat.');
    }


    public function edit($id)
{
    // Ambil role berdasarkan ID dan semua permissions
    $role = Role::findOrFail($id);
    $permissions = Permission::all();

    return view('superadmin.masterdata.role.edit', compact('role', 'permissions'));
}

public function update(Request $request, $id)
{
    // Validasi data input
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'permissions' => 'required|array',
        'permissions.*' => 'string|exists:permissions,name',
        'status' => 'required|string|in:enable,disable',
    ]);

    // Temukan role berdasarkan ID dan perbarui
    $role = Role::findOrFail($id);
    $role->update([
        'name' => $validatedData['name'],
        'status' => $validatedData['status'],
    ]);

    // Sinkronkan permissions
    // $role->syncPermissions($validatedData['permissions']);
    if ($request->has('permissions')) {
        $permissions = $request->input('permissions');
        foreach ($permissions as $permissionName) {
            // Check if the permission exists, otherwise create it
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $role->givePermissionTo($permission);
        }
    }

    

    if ($role->status == 'disable') {
        return redirect()->route('role.index')->with('warning', 'Role berhasil diperbarui tetapi dinonaktifkan.');
    }

    return redirect()->route('role.index')->with('success', 'Role berhasil diperbarui.');
}


public function destroy($id)
{
    // Temukan role berdasarkan ID
    $role = Role::findOrFail($id);
    
    // Hapus role
    $role->delete();

    return redirect()->route('role.index')->with('success', 'Role berhasil dihapus.');
}


}



