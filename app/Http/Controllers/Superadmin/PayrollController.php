<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payroll.index')->only('index');
        $this->middleware('permission:payroll.create')->only(['create', 'store']);
        $this->middleware('permission:payroll.edit')->only(['edit', 'update']);
        $this->middleware('permission:payroll.delete')->only('destroy');
    }
    public function index()
    {
        return view('Superadmin.payroll.index');
    }
}
