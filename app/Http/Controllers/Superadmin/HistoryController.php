<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $employees = Employee::when($search, function ($query, $search) {
            return $query->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%");
        })
            ->whereNotNull('resign_date')
            ->paginate(10);

        return view('superadmin.Employeedata.History.index', compact('employees'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('superadmin.Employeedata.History.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'resign_date' => 'required|date',
            'reason' => 'required|string|max:255',
            'remarks' => 'nullable|string|max:500',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Dapatkan employee yang sesuai dengan employee_id
        $employee = Employee::findOrFail($validatedData['employee_id']);

        // Handle upload file jika ada
        if ($request->hasFile('document')) {
            $validatedData['document'] = $request->file('document')->store('documents', 'public');
        }

        // Update data employee
        $employee->update([
            'resign_date' => $validatedData['resign_date'],
            'reason' => $validatedData['reason'],
            'remarks' => $validatedData['remarks'],
            'document' => $validatedData['document'] ?? $employee->document,
        ]);

        // Redirect setelah menyimpan dengan pesan sukses
        return redirect()->route('history.index')->with('success', 'History added successfully!');
    }


    public function edit($employee_id)
    {
        // Get employee data to edit
        $employee = Employee::where('employee_id', $employee_id)->firstOrFail();
        $employees = Employee::all();

        return view('superadmin.Employeedata.History.edit', compact('employee', 'employees'));
    }

    public function update(Request $request, $employee_id)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'resign_date' => 'nullable|date',
            'reason' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,docx,txt',
        ]);

        // Temukan employee berdasarkan employee_id yang dipilih
        $employee = Employee::findOrFail($employee_id);

        // Perbarui data history yang terkait dengan employee
        $employee->update([
            'resign_date' => $validatedData['resign_date'],
            'reason' => $validatedData['reason'],
            'remarks' => $validatedData['remarks'],
        ]);

        // Jika ada dokumen baru, simpan dokumen tersebut
        if ($request->hasFile('document')) {
            // Hapus dokumen lama jika ada
            if ($employee->document) {
                Storage::disk('public')->delete($employee->document);
            }

            // Simpan dokumen baru
            $validatedData['document'] = $request->file('document')->store('documents', 'public');
            $employee->document = $validatedData['document'];
        }

        // Simpan perubahan
        $employee->save();

        return redirect()->route('history.index')->with('success', 'Employee history updated successfully');
    }




    public function destroy($employee_id)
    {
        $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

        // Delete document if exists
        if ($employee->document) {
            Storage::disk('public')->delete($employee->document);
        }

        // Reset history data
        $employee->update([
            'resign_date' => null,
            'reason' => null,
            'remarks' => null,
            'document' => null,
        ]);

        return redirect()->route('history.index')->with('success', 'History deleted successfully!');
    }
}
