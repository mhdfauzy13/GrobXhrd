<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeBook;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeBookController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:employeebook.index')->only('index');
        $this->middleware('permission:employeebook.create')->only(['create', 'store']);
        $this->middleware('permission:employeebook.edit')->only(['edit', 'update']);
        $this->middleware('permission:employeebook.delete')->only('destroy');
    }

    public function index()
    {
        $employees = Employee::all();
        $employeeBooks = EmployeeBook::with('employee')->get();
        return view('superadmin.employeebooks.index', compact('employees', 'employeeBooks'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('superadmin.employeebooks.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'incident_date' => 'required|date',
            'incident_details' => 'required|string',
            'remarks' => 'nullable|string',
            'category' => 'required|in:violation,warning,reprimand',
        ]);

        EmployeeBook::create([
            'employee_id' => $request->input('employee_id'),
            'incident_date' => $request->input('incident_date'),
            'incident_details' => $request->input('incident_details'),
            'remarks' => $request->input('remarks'),
            'category' => $request->input('category'),
        ]);

        return redirect()->route('superadmin.employeebooks.index')->with('success', 'Employee book successfully created');
    }

    public function edit($employeebook_id)
    {
        $employeeBook = EmployeeBook::findOrFail($employeebook_id);
        $employees = Employee::all();
        return view('superadmin.employeebooks.edit', compact('employeeBook', 'employees'));
    }

    public function update(Request $request, $employeebook_id)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'incident_date' => 'required|date',
            'incident_details' => 'required|string',
            'remarks' => 'nullable|string',
            'category' => 'required|in:violation,warning,reprimand',
        ]);

        $employeeBook = EmployeeBook::findOrFail($employeebook_id);
        $employeeBook->update($request->only([
            'employee_id',
            'incident_date',
            'incident_details',
            'remarks',
            'category',
        ]));

        return redirect()->route('superadmin.employeebooks.index')->with('success', 'Employee book successfully updated');
    }


    public function destroy($employeebook_id)
    {
        $employeeBook = EmployeeBook::findOrFail($employeebook_id);
        $employeeBook->delete();

        return redirect()->route('superadmin.employeebooks.index')->with('success', 'Employee book successfully deleted');
    }
}
