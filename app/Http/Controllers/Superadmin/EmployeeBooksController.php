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
        $this->middleware('permission:employeebook.create')->only(['create', 'store', 'searchEmployee']);
        $this->middleware('permission:employeebook.edit')->only(['edit', 'update']);
        $this->middleware('permission:employeebook.delete')->only('destroy');
        $this->middleware('permission:employeebook.detail')->only('detail');
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $typeOfSearch = $request->query('type_of');

        // Violation
        $violations = EmployeeBook::where('category', 'violation')
            ->when($search, function ($query, $search) {
                return $query->whereHas('employee', function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->when($typeOfSearch, function ($query, $typeOfSearch) {
                return $query->where('type_of', 'like', "%{$typeOfSearch}%");
            })
            ->with('employee')  // Mengambil relasi employee
            ->paginate(6);  // Pagination 6 per halaman

        // Warning
        $warnings = EmployeeBook::where('category', 'warning')
            ->when($search, function ($query, $search) {
                return $query->whereHas('employee', function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->when($typeOfSearch, function ($query, $typeOfSearch) {
                return $query->where('type_of', 'like', "%{$typeOfSearch}%");
            })
            ->with('employee')
            ->paginate(6);

        // Reprimand
        $reprimands = EmployeeBook::where('category', 'reprimand')
            ->when($search, function ($query, $search) {
                return $query->whereHas('employee', function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->when($typeOfSearch, function ($query, $typeOfSearch) {
                return $query->where('type_of', 'like', "%{$typeOfSearch}%");
            })
            ->with('employee')
            ->paginate(6);

        // Kirim data ke view
        return view('superadmin.employeebooks.index', compact('violations', 'warnings', 'reprimands', 'search', 'typeOfSearch'));
    }


    public function searchEmployees(Request $request)
    {
        $query = $request->get('query');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $employees = Employee::where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orderBy('first_name')
            ->get(['employee_id', 'first_name', 'last_name']);

        $employees = $employees->map(function ($employee) {
            $employee->full_name = $employee->first_name . ' ' . $employee->last_name;
            return $employee;
        });

        return response()->json($employees);
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
        // Validasi input dari form
        $validated = $request->validate([
            'category' => 'required|string',
            'employee_id' => 'required|exists:employees,employee_id',
            'type_of' => 'required|string',
            'incident_date' => 'required|date',
            'incident_detail' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        // Menyimpan data ke database
        $employeeBook = new EmployeeBook();
        $employeeBook->category = $validated['category'];
        $employeeBook->employee_id = $validated['employee_id'];
        $employeeBook->type_of = $validated['type_of'];
        $employeeBook->incident_date = $validated['incident_date'];
        $employeeBook->incident_detail = $validated['incident_detail'];
        $employeeBook->remarks = $validated['remarks'];

        // Simpan ke database
        $employeeBook->save();

        // Redirect atau kembalikan response sukses
        return redirect()->route('employeebooks.index')->with('success', 'Data berhasil disimpan!');
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
