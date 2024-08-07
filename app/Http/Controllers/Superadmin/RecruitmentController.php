<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Recruitment;
use Illuminate\Http\Request;

class RecruitmentController extends Controller
{
    public function index()
    {
        return view('superadmin.recruitment.index');
    }

    public function create()
    {
        return view('superadmin.recruitment.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:recruitments,email|min:8',
            'phone_number' => 'required|string',
            'date_of_birth' => 'required|date',
            'last_education' => 'required|string',
            'last_position' => 'required|string',
            'cv_file' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'comment' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);


        Recruitment::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'date_of_birth' => $request->input('date_of_birth'),
            'last_education' => $request->input('last_education'),
            'last_position' => $request->input('last_position'),
            'cv_file' => $request->file('cv_file')->store('cv_files', 'public'),
            'comment' => $request->input('comment'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('recruitment.index')->with('success', 'Recruitment successfully created');
    }
}
