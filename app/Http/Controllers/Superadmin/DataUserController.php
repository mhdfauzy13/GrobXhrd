<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DataUserController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles')->get();
        return view('Superadmin.MasterData.user.index', ['users' => $users]);
    }

    public function create(): View
    {
        return view('Superadmin.MasterData.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|exists:roles,name',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);


        // Cari role berdasarkan nama
        $role = Role::where('name', $request->role)->firstOrFail();


        // Assign role ke user
        $user->assignRole($role);

        return redirect()->route('datauser.index')->with('success', 'Akun berhasil dibuat');
    }

    public function edit($user_id)
    {
        // Cari user berdasarkan user_id
        $user = User::findOrFail($user_id);
    
        // Mendapatkan semua role
        $roles = Role::all();
    
        // Tampilkan form edit dengan data user dan role
        return view('Superadmin.MasterData.user.edit', compact('user', 'roles'));
    }


    public function update(Request $request, $user_id)
    {
        // Cari user berdasarkan user_id
        $user = User::findOrFail($user_id);
    
        // Validasi input
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'role' => 'required|exists:roles,name',
            'password' => 'nullable|min:8|confirmed',
        ]);
    
        // Update user
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->filled('password') ? Hash::make($request->input('password')) : $user->password,
        ]);
    
        // Sinkronisasi role
        $role = Role::where('name', $request->role)->firstOrFail();
        $user->syncRoles($role);
    
        return redirect()->route('datauser.index')->with('success', 'Data user berhasil diupdate');
    }
    


    public function destroy(User $datauser)
    {
        $datauser->delete();
        return redirect()->route('datauser.index')->with('success', 'Data berhasil dihapus!');
    }
}
