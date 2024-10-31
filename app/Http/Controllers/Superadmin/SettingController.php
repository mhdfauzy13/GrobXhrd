<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyName;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:settings.index')->only('index');
        $this->middleware('permission:settings.storeOrUpdate')->only(['storeOrUpdate']);
    }
    public function index()
    {
        $companyNames = CompanyName::all();
        return view('Superadmin.Setting.Companyname.index', compact('companyNames')); 
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name_company' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $company = new CompanyName();
        $company->name_company = $validatedData['name_company'];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('company_images', 'public');
            $company->image = $imagePath;
        }

        $company->save();

        return redirect()->route('settings.index')->with('success', 'Data berhasil disimpan');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name_company' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $company = CompanyName::findOrFail($id);
        $company->name_company = $validatedData['name_company'];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('company_images', 'public');
            $company->image = $imagePath;
        }

        $company->save();

        return redirect()->route('settings.index')->with('success', 'Data berhasil diperbarui');
    }
}
