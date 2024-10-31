<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyName;
use App\Models\SalaryDeduction;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:settings.index')->only('index');
        $this->middleware('permission:settings.company')->only(['store','update']);
        $this->middleware('permission:settings.deductions')->only(['updateLateDeduction','updateEarlyDeduction']);

    }

    public function index()
    {
        $companyNames = CompanyName::all();
        $salaryDeduction = SalaryDeduction::first(); // Mengambil data potongan gaji (hanya satu record)

        return view('Superadmin.Setting.Companyname.index', compact('companyNames', 'salaryDeduction'));
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

    public function updateLateDeduction(Request $request)
    {
        $validatedData = $request->validate([
            'late_deduction' => 'required|string',
        ]);
        if (isset($request->late_deduction)) {
            $validatedData['late_deduction'] = (int) str_replace('.', '', $request->late_deduction);
        }
        $salaryDeduction = SalaryDeduction::firstOrCreate([]);
        $salaryDeduction->late_deduction = $validatedData['late_deduction'];
        $salaryDeduction->save();

        return redirect()->route('settings.index')->with('success', 'Late deduction updated successfully');
    }

    public function updateEarlyDeduction(Request $request)
    {
        $validatedData = $request->validate([
            'early_deduction' => 'required|string',
        ]);

        if (isset($request->early_deduction)) {
            $validatedData['early_deduction'] = (int) str_replace('.', '', $request->early_deduction);
        }

        $salaryDeduction = SalaryDeduction::firstOrCreate([]);
        $salaryDeduction->early_deduction = $validatedData['early_deduction'];
        $salaryDeduction->save();

        return redirect()->route('settings.index')->with('success', 'Early deduction updated successfully');
    }
}
