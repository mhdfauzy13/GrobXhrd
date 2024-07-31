<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Pastikan model yang tepat digunakan, misalnya jika modelnya bukan User maka sesuaikan

class CompanyController extends Controller
{
    public function index()
    {
        return view("superadmin.MasterData.company.index");
    }

    public function create()
    {
        return view('superadmin.MasterData.company.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_company' => 'required|string',
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'required|email|unique:users,email|min:8',
            'status' => 'required|string',
            'google_client_id' => 'required|string',
            'google_client_secret' => 'required|string',
            'google_oauth_scope' => 'required|string',
            'google_json_file' => 'required|string',
            'google_oauth_url' => 'required|url',
        ]);

        User::create([
            'name_company' => $request->input('name_company'),
            'address' => $request->input('address'),
            'phone_number' => $request->input('phone_number'),
            'email' => $request->input('email'),
            'status' => $request->input('status'),
            'google_client_id' => $request->input('google_client_id'),
            'google_client_secret' => $request->input('google_client_secret'),
            'google_oauth_scope' => $request->input('google_oauth_scope'),
            'google_json_file' => $request->input('google_json_file'),
            'google_oauth_url' => $request->input('google_oauth_url'),
        ]);

        return redirect()->route('company.index')->with('success', 'Company successfully created');
    }
}
