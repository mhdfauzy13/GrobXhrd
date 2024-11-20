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
        $this->middleware('permission:recruitment.index')->only(['index', 'show']);
        $this->middleware('permission:recruitment.create')->only(['create', 'store']);
        $this->middleware('permission:recruitment.edit')->only(['edit', 'update']);
        $this->middleware('permission:recruitment.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        // Get the search value from the query string
        $search = $request->query('search');

        // Apply search filter if search term exists
        $recruitments = Recruitment::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })->paginate(10); // Pagination with 10 items per page

        return view('superadmin.recruitment.index', compact('recruitments'));
    }

    public function create()
    {
        return view('superadmin.recruitment.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required|numeric',
            'date_of_birth' => 'required|date',
            'last_education' => 'required|string|in:Elementary School,Junior High School,Senior High School,Vocational High School,Associate Degree 1,Associate Degree 2,Associate Degree 3,Bachelor’s Degree,Master’s Degree,Doctoral Degree',
            'last_position' => 'required|string',
            'apply_position' => 'required|string',
            'cv_file' => 'required|file|mimes:pdf,doc,docx',
            'comment' => 'required|string',
            'status' => 'required|string',
        ], [
            'phone_number.numeric' => 'Phone number must contain only numbers.',
        ]);

        try {
            Recruitment::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone_number' => $request->input('phone_number'),
                'date_of_birth' => $request->input('date_of_birth'),
                'last_education' => $request->input('last_education'),
                'last_position' => $request->input('last_position'),
                'apply_position' => $request->input('apply_position'),
                'cv_file' => $request->file('cv_file')->store('cv_files', 'public'),
                'comment' => $request->input('comment'),
                'status' => $request->input('status'),
            ]);

            return redirect()->route('recruitment.index')->with('success', 'Recruitment successfully created');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function edit($recruitment_id)
    {
        $recruitment = Recruitment::findOrFail($recruitment_id);
        return view('superadmin.recruitment.edit', compact('recruitment'));
    }

    public function update(Request $request, $recruitment_id)
    {
        $recruitment = Recruitment::findOrFail($recruitment_id);

        $validated = $request->validate([
            'name' => 'nullable|string',
            'email' => 'nullable|email|unique:recruitments,email,' . $recruitment_id . ',recruitment_id|min:8',
            'phone_number' => 'nullable|numeric',
            'date_of_birth' => 'nullable|date',
            'last_education' => 'nullable|string|in:Elementary School,Junior High School,Senior High School,Vocational High School,Associate Degree 1,Associate Degree 2,Associate Degree 3,Bachelor’s Degree,Master’s Degree,Doctoral Degree',
            'last_position' => 'nullable|string',
            'apply_position' => 'nullable|string',
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'comment' => 'nullable|string',
            'status' => 'nullable|string|in:Initial Interview,User Interview 1,User Interview 2,Background Check,Offering letter,Accept,Decline',
        ], [
            'phone_number.numeric' => 'Phone number must contain only numbers.',
        ]);

        try {
            $recruitment->name = $validated['name'] ?? $recruitment->name;
            $recruitment->email = $validated['email'] ?? $recruitment->email;
            $recruitment->phone_number = $validated['phone_number'] ?? $recruitment->phone_number;
            $recruitment->date_of_birth = $validated['date_of_birth'] ?? $recruitment->date_of_birth;
            $recruitment->last_education = $validated['last_education'] ?? $recruitment->last_education;
            $recruitment->last_position = $validated['last_position'] ?? $recruitment->last_position;
            $recruitment->apply_position = $validated['apply_position'] ?? $recruitment->apply_position;
            $recruitment->status = $validated['status'] ?? $recruitment->status;

            if ($request->hasFile('cv_file')) {
                $recruitment->cv_file = $request->file('cv_file')->store('cv_files', 'public');
            }

            $recruitment->comment = $validated['comment'] ?? $recruitment->comment;
            $recruitment->save();

            return redirect()->route('recruitment.index')->with('success', 'Recruitment updated successfully');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function show($recruitment_id)
    {
        $recruitment = Recruitment::findOrFail($recruitment_id);
        return view('superadmin.recruitment.show', compact('recruitment'));
    }

    public function destroy($recruitment_id)
    {
        $recruitment = Recruitment::findOrFail($recruitment_id);
        $recruitment->delete();

        return redirect()->route('recruitment.index')->with('success', 'Recruitment deleted successfully');
    }
}
