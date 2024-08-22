<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Offrequest;
use App\Models\User;
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

        $managers = User::where('role', 'manager')->pluck('name', 'user_id');

        return view('employee.offrequest.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'start_event' => 'required|date',
            'end_event' => 'required|date',
            'manager_id' => 'required|exists:users,user_id',
        ]);

        Offrequest::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'start_event' => $request->input('start_event'),
            'end_event' => $request->input('end_event'),
            'manager_id' => $request->input('manager_id'),
        ]);

        return redirect()->route('offrequests.index')->with('success', 'Permohonan cuti diajukan');
    }
}
