<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DataUserController extends Controller
{
    public function index()
    {
        return view('Superadmin.MasterData.user.index');
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
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            // 'role' => 'admin',
            'password' => bcrypt($request->input('password')),
        ]);
        return redirect()->route('datausers.index')->with('success', 'Akun berhasil dibuat');
    }
}
