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
        $roles = Role::with('permissions')->get();

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
        $permissions = Permission::all();
        return view('superadmin.masterdata.role.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name',
            'status' => 'required|string|in:enable,disable',
        ]);

        $role = Role::create([
            'name' => $validatedData['name'],
            'status' => $validatedData['status'],
        ]);

        $role->syncPermissions($validatedData['permissions']);
        if ($role->status == 'disable') {
            return redirect()->route('role.index')->with('warning', 'Role berhasil dibuat tetapi dinonaktifkan.');
        }

        return redirect()->route('role.index')->with('success', 'Role berhasil dibuat.');
    }
}
