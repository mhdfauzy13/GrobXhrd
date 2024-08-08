<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('Superadmin.Employeedata.Attendence.index');
    }
}
