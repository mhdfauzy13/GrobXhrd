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

        return view('superadmin.masterdata.role.index', compact('activeRoles', 'disabledRoles', 'roles'));
    }

    public function create()
    {
        // Ambil semua permissions untuk ditampilkan di form create
        $permissions = Permission::all();
        return view('superadmin.masterdata.role.create', compact('permissions'));
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
        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        return redirect()->route('role.index')->with('success', 'Role berhasil dibuat.');
    }


    public function edit($id)
    {
        // Ambil role berdasarkan ID
        $role = Role::findOrFail($id);
        // Ambil semua permissions
        $permissions = Permission::all();
        // Ambil permissions yang sudah dimiliki oleh role
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('superadmin.masterdata.role.edit', compact('role', 'permissions', 'rolePermissions'));
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

        // Ambil role berdasarkan ID
        $role = Role::findOrFail($id);
        // Update role
        $role->update([
            'name' => $validatedData['name'],
            'status' => $validatedData['status'],
        ]);

        // Update permissions
        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

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



