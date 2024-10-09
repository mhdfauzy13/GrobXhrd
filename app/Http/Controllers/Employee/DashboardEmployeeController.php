<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardEmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboardemployee.view')->only(['index']);
    }
    public function index()
    {
        return view('Employee.dashboard.index');
    }
}
