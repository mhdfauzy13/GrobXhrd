<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Offrequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role; 

class OffemployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:offrequest.index')->only(['index']);
        $this->middleware('permission:offrequest.create')->only(['create']);
        $this->middleware('permission:offrequest.store')->only(['store']);

    }
    public function index()
    {
        // $offrequests = Offrequest::all();

        // return view('employee.offrequest.index', ['offrequests' => $offrequests]);

        $offrequests = Offrequest::with('manager')->get(); // Mengambil semua data offrequest beserta relasi ke manager
        // dd($offrequests);

        return view('employee.offrequest.index', compact('offrequests'));
    }

    public function create()
    {
        // $managers = User::managers()->get();

        // Mengambil user dengan role 'manager' menggunakan Spatie Laravel Permission package
        $managers = User::select('name', 'user_id')
            ->whereHas('roles', function($query) {
                $query->where('roles.id', 3); // ID role 'manager'
            })
            ->get();

        return view('employee.offrequest.create', compact('managers'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'title' => 'required|string',
    //         'description' => 'required|string',
    //         'start_event' => 'required|date',
    //         'end_event' => 'required|date',
    //         'manager_id' => 'required|exists:users,id', // Validasi dengan 'id'
    //     ]);

        

    //     Offrequest::create([
    //         'user_id' => Auth::id(),
    //         'name' => Auth::user()->name,
    //         'email' => Auth::user()->email,
    //         'title' => $request->input('title'),
    //         'description' => $request->input('description'),
    //         'start_event' => $request->input('start_event'),
    //         'end_event' => $request->input('end_event'),
    //         'manager_id' => $request->input('manager_id'),
    //     ]);

    //     return redirect()->route('offrequests.index')->with('success', 'Permohonan cuti diajukan');
    // }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string',
        'description' => 'required|string',
        'start_event' => 'required|date',
        'end_event' => 'required|date',
        'manager_id' => 'required|exists:users,id',
    ]);

    $user = Auth::user();
    // dd('$offrequests');


    $offrequest = Offrequest::create([
        'user_id' => Auth::id(),
        'name' => Auth::user()->name,
        'email' => Auth::user()->email,
        'title' => $request->input('title'),
        'description' => $request->input('description'),
        'start_event' => $request->input('start_event'),
        'end_event' => $request->input('end_event'),
        'manager_id' => $request->input('manager_id'),
    ]);

    Log::info('Offrequest Saved:', $offrequest->toArray());

    return redirect()->route('offrequests.index')->with('success', 'Permohonan cuti diajukan');
}

}
