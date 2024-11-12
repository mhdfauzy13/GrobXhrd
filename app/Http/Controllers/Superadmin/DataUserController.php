<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DataUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user.index')->only(['index']);
        $this->middleware('permission:user.create')->only(['create', 'store']);
        $this->middleware('permission:user.edit')->only(['edit', 'update']);
        $this->middleware('permission:user.delete')->only('destroy');
    }

    public function index(): View
    {
        $users = User::with('roles')->paginate(10);
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

        // Membuat user baru
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        // Menetapkan role untuk user
        $user->assignRole($request->input('role'));

        // Redirect kembali dengan session success
        return redirect()->route('datauser.index')->with('success', 'Akun berhasil dibuat');
    }

    public function edit($userId)
    {
        $user = User::findOrFail($userId); // Mencari user berdasarkan user_id, atau tampilkan 404 jika tidak ditemukan

        $roles = Role::all(); // Ambil semua role yang tersedia
        $isEmployee = $user->employee()->exists(); // Pengecekan apakah user terkait dengan employee

        return view('Superadmin.MasterData.user.update', [
            'user' => $user,
            'roles' => $roles,
            'isEmployee' => $isEmployee, // Berikan info apakah user terkait dengan employee
        ]);
    }

    public function update(Request $request, $userId)
    {
        $user = User::findOrFail($userId); 

        $request->validate([
            'name' => 'required|string',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->user_id, 'user_id')],
            'role' => 'required|string',
            'password' => 'nullable|min:8|confirmed',
        ]);

        if (!$user->employee()->exists()) {
            $user->name = $request->input('name');
            $user->email = $request->input('email');
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();
        $user->syncRoles($request->input('role')); 

        if ($user->employee()->exists()) {
            $user->employee()->update(['user_id' => $user->user_id]);
        }

        return redirect()->route('datauser.index')->with('success', 'Data Berhasil Disimpan!');
    }

    public function destroy($userId)
    {
        $user = User::findOrFail($userId); // Perbaikan: Menggunakan findOrFail

        $user->delete();
        return redirect()->route('datauser.index')->with('success', 'Data berhasil dihapus!');
    }
}
