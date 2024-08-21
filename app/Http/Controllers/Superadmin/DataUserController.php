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
            'role' => 'required|string',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $user->assignRole($request->input('role'));

        return redirect()->route('datausers.index')->with('success', 'Akun berhasil dibuat');
    }
    public function edit($userId)
    {
        $user = User::where('user_id', $userId)->first();

        if (!$user) {
            return redirect()
                ->route('datausers.index')
                ->with(['error' => 'Data tidak ditemukan!']);
        }

        $roles = Role::all();

        return view('Superadmin.MasterData.user.update', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, $userId)
    {
        $user = User::where('user_id', $userId)->first();

        if (!$user) {
            return redirect()
                ->route('datausers.index')
                ->with(['error' => 'Data tidak ditemukan!']);
        }

        $request->validate([
            'name' => 'required|string',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->user_id, 'user_id')],
            'role' => 'required|string',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        $user->syncRoles($request->input('role'));

        return redirect()
            ->route('datausers.index')
            ->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function destroy(User $datauser)
    {
        $datauser->delete();
        return redirect()->route('datausers.index')->with('success', 'Data berhasil dihapus!');
    }
}
