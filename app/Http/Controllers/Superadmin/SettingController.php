<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyName;
use App\Models\SalaryDeduction;
use App\Models\WorkdaySetting;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:settings.index')->only('index');
        $this->middleware('permission:settings.company')->only(['store','update']);
        $this->middleware('permission:settings.deductions')->only(['updateLateDeduction','updateEarlyDeduction']);
        $this->middleware('permission:settings.worksdays')->only(['updateWorkdays']);


    }

    public function index()
    {
        $companyNames = CompanyName::all();
        $salaryDeduction = SalaryDeduction::first(); // Mengambil data potongan gaji (hanya satu record)
        $workdaySetting = WorkdaySetting::first(); 

        return view('Superadmin.Setting.index', compact('companyNames', 'salaryDeduction', 'workdaySetting'));
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

    public function salarydeductions(Request $request)
    {
        $validatedData = $request->validate([
            'late_deduction' => 'required|string',
            'early_deduction' => 'required|string',
        ]);

        if (isset($request->late_deduction)) {
            $validatedData['late_deduction'] = (int) str_replace('.', '', $request->late_deduction);
        }

        if (isset($request->early_deduction)) {
            $validatedData['early_deduction'] = (int) str_replace('.', '', $request->early_deduction);
        }

        $salaryDeduction = SalaryDeduction::firstOrCreate([]);
        $salaryDeduction->late_deduction = $validatedData['late_deduction'];
        $salaryDeduction->early_deduction = $validatedData['early_deduction'];
        $salaryDeduction->save();

        return redirect()->route('settings.index')->with('success', 'Early deduction updated successfully');
    }

    public function updateWorkdays(Request $request)
    {
        $validatedData = $request->validate([
            'effective_days' => 'required|array',
            'effective_days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        ]);
    
        $workdaySetting = WorkdaySetting::firstOrCreate([]);
        $workdaySetting->effective_days = $validatedData['effective_days'];
        
        // Hitung jumlah hari kerja efektif per bulan
        $weeklyWorkdays = count($validatedData['effective_days']);
        $monthlyWorkdays = $weeklyWorkdays * 4; // Asumsi 4 minggu per bulan
        $workdaySetting->monthly_workdays = $monthlyWorkdays;
    
        $workdaySetting->save();
    
        return redirect()->route('settings.index')->with('success', 'Workday settings updated successfully');
    }
    
}
