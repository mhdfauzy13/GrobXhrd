<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\Employee;
use App\Models\EmployeeBook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmployeeBooksController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:employeebook.index')->only('index');
        $this->middleware('permission:employeebook.create')->only(['create', 'store']);
        $this->middleware('permission:employeebook.edit')->only(['edit', 'update']);
        $this->middleware('permission:employeebook.delete')->only('destroy');
        $this->middleware('permission:employeebook.detail')->only('detail');
    }

    public function index(Request $request)
    {

        $search = $request->query('search');

        $violations = EmployeeBook::where('category', 'violation')
            ->when($search, function ($query, $search) {
                return $query->whereHas('employee', function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->with('employee')
            ->paginate(6);

        $warnings = EmployeeBook::where('category', 'warning')
            ->when($search, function ($query, $search) {
                return $query->whereHas('employee', function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->with('employee')
            ->paginate(6);

        $reprimands = EmployeeBook::where('category', 'reprimand')
            ->when($search, function ($query, $search) {
                return $query->whereHas('employee', function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->with('employee')
            ->paginate(6);

        $employees = Employee::all(); // Ambil semua data karyawan (jika dibutuhkan)

        // Kirim data ke view
        return view('superadmin.employeebooks.index', compact('violations', 'warnings', 'reprimands', 'employees', 'search'));
    }



    public function create(Request $request)
    {
        // Urutkan karyawan berdasarkan nama depan dan nama belakang
        $employees = Employee::orderBy('first_name')->orderBy('last_name')->get();

        $category = $request->query('category', 'violation'); // Set kategori default
        return view('superadmin.employeebooks.create', compact('employees', 'category'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'incident_date' => 'required|date',
            'incident_detail' => 'required|string',
            'remarks' => 'required|string',
            'category' => 'required|string',
            'type_of' => 'required|in:SOP,Administrative,Behavior',
        ]);

        // Simpan data ke database
        EmployeeBook::create($request->all());

        return redirect()->route('employeebooks.index', ['category' => $request->category])->with('success', 'Employee book created successfully.');
    }
    public function edit($id)
    {
        $employeeBook = EmployeeBook::findOrFail($id);
        $employees = Employee::all(); // Ambil data karyawan untuk dropdown

        return view('superadmin.employeebooks.edit', compact('employeeBook', 'employees'));
    }


    public function update(Request $request, EmployeeBook $employeeBook)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'incident_date' => 'required|date',
            'incident_detail' => 'required|string',
            'remarks' => 'required|string',
            'category' => 'required|string',
            'type_of' => 'required|in:SOP,Administrative,Behavior',
        ]);

        // Update data di database
        $employeeBook->update($request->all());

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('employeebooks.index')->with('success', 'Employee book updated successfully.');
    }

    public function destroy(EmployeeBook $employeeBook)
    {
        $employeeBook->delete();

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('employeebooks.index')->with('success', 'Employee book deleted successfully.');
    }

    // Method untuk menampilkan detail EmployeeBook
    public function detail(EmployeeBook $employeeBook)
    {
        return view('superadmin.employeebooks.detail', compact('employeeBook'));
    }
}
