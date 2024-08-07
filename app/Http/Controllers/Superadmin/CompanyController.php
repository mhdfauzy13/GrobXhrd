<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        return view("superadmin.MasterData.company.index", compact('companies'));
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
            'email' => 'required|email|unique:companies,email',
        ]);

        Company::create([
            'name_company' => $request->input('name_company'),
            'address' => $request->input('address'),
            'phone_number' => $request->input('phone_number'),
            'email' => $request->input('email'),
            'status' => $request->input('status'), // Pastikan status juga ditambahkan jika diperlukan
        ]);

        return redirect()->route('company.index')->with('success', 'Company successfully created');
    }

    public function destroy($company_id)
    {
        $company = Company::where('company_id', $company_id)->first();

        if (!$company) {
            return redirect()->route('company.index')->with('error', 'Company not found');
        }

        $company->delete();

        return redirect()->route('company.index')->with('success', 'Company successfully deleted');
    }

    public function edit($company_id)
    {
        $company = Company::where('company_id', $company_id)->first();

        if (!$company) {
            return redirect()->route('company.index')->with('error', 'Company not found');
        }
        return view('superadmin.MasterData.company.edit', compact('company'));
    }

    public function update(Request $request, $company_id)
    {
        $request->validate([
            'name_company' => 'required|string',
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'required|email|unique:companies,email,' . $company_id . ',company_id', // Pastikan validasi sesuai
            'status' => 'required|string|in:active,inactive',
        ]);

        $company = Company::where('company_id', $company_id)->first();

        if (!$company) {
            return redirect()->route('company.index')->with('error', 'Company not found');
        }

        $company->update($request->only([
            'name_company',
            'address',
            'phone_number',
            'email',
            'status',
        ]));

        return redirect()->route('company.index')->with('success', 'Company successfully updated');
    }

    public function show($company_id)
    {
        $company = Company::where('company_id', $company_id)->firstOrFail();
        return view('superadmin.MasterData.company.show', compact('company'));
    }
}
