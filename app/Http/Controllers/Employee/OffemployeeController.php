<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Offrequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OffemployeeController extends Controller
{
    public function index()
    {
        $offrequests = Offrequest::all();

        return view('employee.offrequest.index', ['offrequests' => $offrequests]);
    }

    public function create()
    {
        return view('employee.offrequest.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'mtitle' => 'required|string',
            'description' => 'required|string',
            'start_event' => 'required|date',
            'end_event' => 'required|date',
        ]);

        Offrequest::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'mtitle' => $request->input('mtitle'),
            'description' => $request->input('description'),
            'start_event' => $request->input('start_event'),
            'end_event' => $request->input('end_event'),
        ]);

        return redirect()->route('offrequests.index')->with('success', 'Permohonan cuti diajukan');
    }
}
