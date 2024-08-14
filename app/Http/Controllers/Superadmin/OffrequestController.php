<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Offrequest;
use Illuminate\Http\Request;

class OffrequestController extends Controller
{
    public function index()
    {
        $offrequests = Offrequest::all();
        return view('superadmin.employeedata.offrequest.index', compact('offrequests'));
    }

    public function create()
    {
        return view('superadmin.employeedata.offrequest.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:offrequests,email',
            'mtitle' => 'required|string',
            'description' => 'required|string',
            'start_event' => 'required|date',
            'end_event' => 'required|date',
        ]);

        // Simpan data ke database
        Offrequest::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mtitle' => $request->input('mtitle'),
            'description' => $request->input('description'),
            'start_event' => $request->input('start_event'),
            'end_event' => $request->input('end_event'),
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('offrequests.index')->with('success', 'Offrequest successfully created');
    }
}
