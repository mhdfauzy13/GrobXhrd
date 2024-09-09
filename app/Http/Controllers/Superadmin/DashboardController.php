<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->roles->first();

        // Periksa apakah pengguna memiliki role
        if (!$role) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses.');
        }

        // Cek role dan arahkan ke view yang sesuai
        switch ($role->name) {
            case 'employee':
                return view('employee.dashboard.index');
            case 'manager':
                return view('manager.dashboard.index');
            case 'superadmin':
                return view('superadmin.dashboard.index');
            default:
                return redirect()->route('login')->with('error', 'Role tidak dikenali.');
        }
    }
}
