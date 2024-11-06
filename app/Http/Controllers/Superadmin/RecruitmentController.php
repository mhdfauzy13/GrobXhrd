<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Recruitment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecruitmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:recruitment.index')->only('index');
        $this->middleware('permission:recruitment.create')->only(['create', 'store']);
        $this->middleware('permission:recruitment.edit')->only(['edit', 'update']);
        $this->middleware('permission:recruitment.delete')->only('destroy');
    }

    public function index()
    {
        $recruitments = Recruitment::paginate(10);
        return view('superadmin.recruitment.index', compact('recruitments'));
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
            'apply_position' => 'required|string', // Validasi untuk apply position
            'cv_file' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'comment' => 'nullable|string',
            'status' => 'required|in:Initial Interview,User Interview 1,User Interview 2,Background Check,Offering letter,Accept,Decline',
        ]);

        Recruitment::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'date_of_birth' => $request->input('date_of_birth'),
            'last_education' => $request->input('last_education'),
            'last_position' => $request->input('last_position'),
            'apply_position' => $request->input('apply_position'), // Menyimpan data apply position
            'cv_file' => $request->file('cv_file')->store('cv_files', 'public'),
            'comment' => $request->input('comment'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('recruitment.index')->with('success', 'Recruitment successfully created');
    }

    public function edit($recruitment_id)
    {
        $recruitment = Recruitment::findOrFail($recruitment_id);
        return view('superadmin.recruitment.edit', compact('recruitment'));
    }

    public function update(Request $request, $recruitment_id)
    {
        $recruitment = Recruitment::findOrFail($recruitment_id);

        $request->validate([
            'name' => 'nullable|string',
            'email' => 'nullable|email|unique:recruitments,email,' . $recruitment_id . ',recruitment_id|min:8',
            'phone_number' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'last_education' => 'nullable|string',
            'last_position' => 'nullable|string',
            'apply_position' => 'nullable|string', // Validasi untuk apply position
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'comment' => 'nullable|string',
            'status' => 'nullable|in:Initial Interview,User Interview 1,User Interview 2,Background Check,Offering letter,Accept,Decline',
        ]);

        // Perbarui field yang ada di request
        $recruitment->name = $request->input('name');
        $recruitment->email = $request->input('email');
        $recruitment->phone_number = $request->input('phone_number');
        $recruitment->date_of_birth = $request->input('date_of_birth');
        $recruitment->last_education = $request->input('last_education');
        $recruitment->last_position = $request->input('last_position');
        $recruitment->apply_position = $request->input('apply_position'); // Perbarui apply position
        if ($request->hasFile('cv_file')) {
            Storage::disk('public')->delete($recruitment->cv_file);
            $recruitment->cv_file = $request->file('cv_file')->store('cv_files', 'public');
        }
        $recruitment->comment = $request->input('comment');
        $recruitment->status = $request->input('status');

        $recruitment->save();

        return redirect()->route('recruitment.index')->with('success', 'Recruitment successfully updated');
    }

    public function destroy($recruitment_id)
    {
        $recruitment = Recruitment::findOrFail($recruitment_id);

        if ($recruitment->cv_file) {
            Storage::disk('public')->delete($recruitment->cv_file);
        }
        $recruitment->delete();

        return redirect()->route('recruitment.index')->with('success', 'Recruitment successfully deleted');
    }
}
