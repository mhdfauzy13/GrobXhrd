<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:employee.index')->only('index', 'show', 'searchEmployees');
        $this->middleware('permission:employee.create')->only(['create', 'store']);
        $this->middleware('permission:employee.edit')->only(['edit', 'update']);
        $this->middleware('permission:employee.delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $search = $request->get('search');

        $employees = Employee::query()
            ->where(function ($query) use ($search) {
                $query
                    ->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('address', 'like', "%$search%");
            })
            ->orderBy('first_name', 'asc')
            ->orderBy('last_name', 'asc')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'employees' => $employees,
            ]);
        }
        // Jika pencarian tidak menemukan hasil
        if ($employees->isEmpty() && $search) {
            return redirect()
                ->route('employee.index')
                ->withErrors(['No data found for the search term: ' . $search]);
        }

        return view('Superadmin.Employeedata.Employee.index', compact('employees'));
    }

    public function create(): View
    {
        return view('Superadmin.Employeedata.Employee.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate(
            [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|unique:employees,email',
                'check_in_time' => 'required|date_format:H:i',
                'check_out_time' => 'required|date_format:H:i|after:check_in_time',
                'place_birth' => 'required|string',
                'date_birth' => 'nullable|date',
                'identity_number' => 'nullable|regex:/^[0-9]+$/|max:20',
                'address' => 'nullable|string',
                'current_address' => 'nullable|string',
                'blood_type' => 'nullable|in:A,B,AB,O',
                'blood_rhesus' => 'nullable|string',
                'phone_number' => 'required|numeric',
                'hp_number' => 'required|numeric',
                'marital_status' => 'nullable|in:Single,Married,Widow,Widower',
                'last_education' => 'nullable|in:Elementary School,Junior High School,Senior High School,Vocational High School,Associate Degree 1,Associate Degree 2,Associate Degree 3,Bachelor’s Degree,Master’s Degree,Doctoral Degree',
                'degree' => 'nullable|string',
                'starting_date' => 'nullable|date',
                'interview_by' => 'nullable|string',
                'current_salary' => 'nullable|string',
                'insurance' => 'nullable|boolean',
                'serious_illness' => 'nullable|string',
                'hereditary_disease' => 'nullable|string',
                'emergency_contact' => 'nullable|string',
                'relations' => 'nullable|in:Parent,Guardian,Husband,Wife,Sibling',
                'emergency_number' => 'required|numeric',
                'status' => 'nullable|in:Active,Inactive',
            ],
            [
                'identity_number.regex' => 'Identity number must only contain numbers.',
                'identity_number.max' => 'Identity number cannot exceed 20 digits.',
                'phone_number.numeric' => 'Phone number must contain only numbers.',
                'phone_number.max' => 'Phone number cannot exceed 15 digits.',
                'hp_number.numeric' => 'HP number must only contain numbers.',
                'hp_number.max' => 'HP number cannot exceed 15 digits.',
                'emergency_number.numeric' => 'Emergency number must only contain numbers.',
                'emergency_number.max' => 'Emergency number cannot exceed 15 digits.',
            ],
        );

        // Hapus titik pada `current_salary` dan konversi ke integer
        if (isset($request->current_salary)) {
            $validatedData['current_salary'] = (int) str_replace('.', '', $request->current_salary);
        }

        try {
            // Proses penyimpanan data employee
            $employee = Employee::create($validatedData);

            // Proses penyimpanan data user
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => bcrypt('defaultpassword'),
                'employee_id' => $employee->employee_id,
            ]);

            $employee->user_id = $user->user_id;
            $employee->save();

            return redirect()
                ->route('datauser.edit', $user->user_id)
                ->with('success', 'Employee created successfully.');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function edit($employee)
    {
        $employeeModel = Employee::where('employee_id', $employee)->first();

        if (!$employeeModel) {
            return redirect()->route('employee.index')->with('error', 'Data tidak ditemukan!');
        }

        return view('Superadmin.Employeedata.Employee.update', compact('employeeModel'));
    }

    public function update(Request $request, $employeeId)
    {
        $employeeModel = Employee::findOrFail($employeeId);

        // Validasi input
        $validatedData = $request->validate(
            [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|unique:employees,email,' . $employeeModel->employee_id . ',employee_id',
                'check_in_time' => 'nullable|date_format:H:i:s',
                'check_out_time' => 'nullable|date_format:H:i:s|after:check_in_time',
                'place_birth' => 'required|string',
                'date_birth' => 'nullable|date',
                'identity_number' => 'nullable|regex:/^[0-9]+$/|max:20',
                'address' => 'nullable|string',
                'current_address' => 'nullable|string',
                'blood_type' => 'nullable|in:A,B,AB,O',
                'blood_rhesus' => 'nullable|string',
                'phone_number' => 'required|numeric',
                'hp_number' => 'required|numeric',
                'marital_status' => 'nullable|in:Single,Married,Widow,Widower',
                'last_education' => 'nullable|in:Elementary School,Junior High School,Senior High School,Vocational High School,Associate Degree 1,Associate Degree 2,Associate Degree 3,Bachelor’s Degree,Master’s Degree,Doctoral Degree',
                'degree' => 'nullable|string',
                'starting_date' => 'nullable|date',
                'interview_by' => 'nullable|string',
                'current_salary' => 'nullable|string',
                'insurance' => 'nullable|boolean',
                'serious_illness' => 'nullable|string',
                'hereditary_disease' => 'nullable|string',
                'emergency_contact' => 'nullable|string',
                'relations' => 'nullable|in:Parent,Guardian,Husband,Wife,Sibling',
                'emergency_number' => 'required|numeric',
                'status' => 'nullable|in:Active,Inactive',
            ],
            [
                'identity_number.regex' => 'Identity number must only contain numbers.',
                'identity_number.max' => 'Identity number cannot exceed 20 digits.',
                'phone_number.numeric' => 'Phone number must contain only numbers.',
                'phone_number.max' => 'Phone number cannot exceed 15 digits.',
                'hp_number.numeric' => 'HP number must only contain numbers.',
                'hp_number.max' => 'HP number cannot exceed 15 digits.',
                'emergency_number.numeric' => 'Emergency number must only contain numbers.',
                'emergency_number.max' => 'Emergency number cannot exceed 15 digits.',
            ],
        );

        // Menghapus pemisah ribuan dan mengonversi ke integer
        if (isset($request->current_salary)) {
            $validatedData['current_salary'] = (int) str_replace('.', '', $request->input('current_salary'));
        }

        // Update semua field lainnya
        $employeeModel->fill($validatedData);

        try {
            $employeeModel->save();

            // Update data User yang terkait
            if ($employeeModel->user) {
                $employeeModel->user->name = $request->input('first_name') . ' ' . $request->input('last_name');
                $employeeModel->user->email = $request->input('email');
                $employeeModel->user->save();
            }

            return redirect()->route('employee.index')->with('success', 'Data Employee berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($employee_id)
    {
        $employee = Employee::where('employee_id', $employee_id)->first();

        $employee->delete();
        return redirect()->route('employee.index')->with('success', 'Data berhasil dihapus!');
    }

    public function show($employee_id)
    {
        $employee = Employee::where('employee_id', $employee_id)->firstOrFail();

        return view('Superadmin.Employeedata.Employee.show', compact('employee'));
    }

    public function searchEmployees(Request $request)
    {
        $query = $request->get('query');
        $employees = Employee::where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orderBy('first_name') // Mengurutkan berdasarkan first_name
            ->get(['employee_id', 'first_name', 'last_name']); // Ambil kolom yang dibutuhkan

        // Gabungkan nama depan dan nama belakang sebagai nama lengkap
        $employees = $employees->map(function ($employee) {
            $employee->full_name = $employee->first_name . ' ' . $employee->last_name;
            return $employee;
        });

        return response()->json($employees);
    }
}
