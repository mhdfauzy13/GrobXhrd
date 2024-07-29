<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class attendance extends Controller
{
    public function index()
    {
        return view('Superadmin.employeedata.attendace.index');
    }

}
