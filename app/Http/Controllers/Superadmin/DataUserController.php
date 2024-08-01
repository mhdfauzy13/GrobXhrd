<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

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
            'password' => bcrypt($request->input('password')),
        ]);

        $user->assignRole($request->input('role'));

        return redirect()->route('datausers.index')->with('success', 'Akun berhasil dibuat');
    }

    public function destroy(\App\Models\User $datauser)
    {
        $datauser->delete();

        return redirect()->route('datausers.index')->with('success', 'Data berhasil dihapus!');
    }
}
