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
        return view('superadmin.masterdata.role.index', compact('roles'));
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
        $role->syncPermissions($validatedData['permissions']);

        return redirect()->route('role.index')->with('success', 'Role berhasil dibuat.');
    }
}
