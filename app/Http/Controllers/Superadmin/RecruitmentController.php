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

        // Validasi custom untuk memastikan email unik
        if ($request->input('email') !== $recruitment->email) {
            $count = Recruitment::where('email', $request->input('email'))
                ->where('recruitment_id', '<>', $recruitment_id)
                ->count();

            if ($count > 0) {
                return redirect()->back()->withErrors('Email sudah digunakan.');
            }
        }

        // Validasi form lainnya
        $request->validate([
            'name' => 'nullable|string', // Nama tidak wajib
            'email' => 'nullable|email|unique:recruitments,email,' . $recruitment_id . ',recruitment_id|min:8', // Email tidak wajib
            'phone_number' => 'nullable|string', // Nomor telepon tidak wajib
            'date_of_birth' => 'nullable|date', // Tanggal lahir tidak wajib
            'last_education' => 'nullable|string', // Pendidikan terakhir tidak wajib
            'last_position' => 'nullable|string', // Posisi terakhir tidak wajib
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // CV file optional
            'comment' => 'nullable|string',
            'status' => 'nullable|in:Initial Interview,User Interview 1,User Interview 2,Background Check,Offering letter,Accept,Decline',
        ]);

        // Hanya perbarui kolom yang ada di request
        if ($request->has('name')) {
            $recruitment->name = $request->input('name');
        }
        if ($request->has('email')) {
            $recruitment->email = $request->input('email');
        }
        if ($request->has('phone_number')) {
            $recruitment->phone_number = $request->input('phone_number');
        }
        if ($request->has('date_of_birth')) {
            $recruitment->date_of_birth = $request->input('date_of_birth');
        }
        if ($request->has('last_education')) {
            $recruitment->last_education = $request->input('last_education');
        }
        if ($request->has('last_position')) {
            $recruitment->last_position = $request->input('last_position');
        }
        if ($request->hasFile('cv_file')) {
            if ($recruitment->cv_file) {
                Storage::disk('public')->delete($recruitment->cv_file);
            }
            $recruitment->cv_file = $request->file('cv_file')->store('cv_files', 'public');
        }
        if ($request->has('comment')) {
            $recruitment->comment = $request->input('comment');
        }
        if ($request->has('status')) {
            $recruitment->status = $request->input('status');
        }

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
