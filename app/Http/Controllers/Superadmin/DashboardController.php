<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard.view')->only(['index']);
    }
    public function index()
    {
        return view('Superadmin.dashboard.index');
    }
}
