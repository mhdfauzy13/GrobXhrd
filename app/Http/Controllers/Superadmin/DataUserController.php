<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataUserController extends Controller
{
    public function index()
    {
        return view('Superadmin.MasterData.user.index');
    }
}
