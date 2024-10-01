<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
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
        Auth::user()->unreadNotifications->markAsRead();

        return view('Superadmin.dashboard.index');
    }
}
