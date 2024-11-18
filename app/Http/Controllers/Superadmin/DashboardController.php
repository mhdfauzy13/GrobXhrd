<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User; // Pastikan model User diimport
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard.view')->only(['index']);
    }

    public function index()
    {
        // Mengambil jumlah total karyawan
        $totalEmployees = Employee::count();

        // Mengambil jumlah total pengguna (users)
        $totalUsers = User::count();

        // Menandai notifikasi sebagai sudah dibaca
        Auth::user()->unreadNotifications->markAsRead();

        // Mengirim data ke view
        return view('Superadmin.dashboard.index', compact('totalEmployees', 'totalUsers'));
    }
}
