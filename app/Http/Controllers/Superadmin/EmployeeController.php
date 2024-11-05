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
        $this->middleware('permission:employee.index')->only('index', 'show');
        $this->middleware('permission:employee.create')->only(['create', 'store']);
        $this->middleware('permission:employee.edit')->only(['edit', 'update']);
        $this->middleware('permission:employee.destroy')->only('destroy');
    }
    public function index(): View
    {
        $employees = Employee::paginate(10);
        return view('Superadmin.Employeedata.Employee.index', compact('employees'));
    }

    public function create(): View
    {
        return view('Superadmin.Employeedata.Employee.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:employees,email',
            'check_in_time' => 'required|date_format:H:i',
            'check_out_time' => 'required|date_format:H:i|after:check_in_time',
            'place_birth' => 'required|string',
            'date_birth' => 'nullable|date',
            'personal_no' => 'nullable|string',
            'address' => 'nullable|string',
            'current_address' => 'nullable|string',
            'blood_type' => 'nullable|in:A,B,AB,O',
            'blood_rhesus' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'hp_number' => 'nullable|string',
            'marital_status' => 'nullable|in:single,married,widow,widower',
            'last_education' => 'nullable|in:SD,SMP,SMA,SMK,D1,D2,D3,S1,S2,S3',
            'degree' => 'nullable|string',
            'starting_date' => 'nullable|date',
            'interview_by' => 'nullable|string',
            'current_salary' => 'nullable|string',
            'insurance' => 'nullable|boolean',
            'serious_illness' => 'nullable|string',
            'hereditary_disease' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
            'relations' => 'nullable|string',
            'emergency_number' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        // Hapus titik pada `current_salary` dan konversi ke integer
        if (isset($request->current_salary)) {
            $validatedData['current_salary'] = (int) str_replace('.', '', $request->current_salary);
        }
        // Membuat employee
        $employee = Employee::create($validatedData);

        // Membuat user dengan data dari employee
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => bcrypt('defaultpassword'), // Password default
            'employee_id' => $employee->employee_id, // Set employee_id di sini
        ]);

        // Memperbarui `user_id` pada employee
        $employee->user_id = $user->user_id;
        $employee->save();

        // Redirect ke halaman edit user (gunakan GET method)
        return redirect()
            ->route('datauser.edit', $user->user_id) // Menggunakan datauser.edit
            ->with('success', 'Employee created. Now configure user details.');
    }

    public function edit($employee)
    {
        $employeeModel = Employee::where('employee_id', $employee)->first();

        if (!$employeeModel) {
            return redirect()->route('employee.index')->with('error', 'Data tidak ditemukan!');
        }

        // Format `current_salary` untuk tampilan di view
        $employeeModel->current_salary = number_format($employeeModel->current_salary, 0, ',', '.');

        return view('Superadmin.Employeedata.Employee.update', compact('employeeModel'));
    }

    public function update(Request $request, $employeeId)
    {
        // dd($request->all());
        $employeeModel = Employee::where('employee_id', $employeeId)->first();

        if (!$employeeModel) {
            return redirect()->route('employee.index')->with('error', 'Data tidak ditemukan!');
        }

        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:employees,email,' . $employeeModel->employee_id . ',employee_id',
            'check_in_time' => 'required|date_format:H:i',
            'check_out_time' => 'required|date_format:H:i|after:check_in_time',
            'place_birth' => 'required|string',
            'date_birth' => 'nullable|date',
            'personal_no' => 'nullable|string',
            'address' => 'nullable|string',
            'current_address' => 'nullable|string',
            'blood_type' => 'nullable|in:A,B,AB,O',
            'blood_rhesus' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'hp_number' => 'nullable|string',
            'marital_status' => 'nullable|in:single,married,widow,widower',
            'last_education' => 'nullable|in:SD,SMP,SMA,SMK,D1,D2,D3,S1,S2,S3',
            'degree' => 'nullable|string',
            'starting_date' => 'nullable|date',
            'interview_by' => 'nullable|string',
            'current_salary' => 'nullable|string',
            'insurance' => 'nullable|boolean',
            'serious_illness' => 'nullable|string',
            'hereditary_disease' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
            'relations' => 'nullable|string',
            'emergency_number' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);
        // Hapus titik pada `current_salary` dan konversi ke integer
        if (isset($request->current_salary)) {
            $current_salary = (int) str_replace('.', '', $request->current_salary);
            $validatedData['current_salary'] = $current_salary; // Simpan ke validatedData
        }

        $employeeModel->update($request->all());

        $user = User::where('employee_id', $employeeModel->employee_id)->first();

        if ($user) {
            $user->name = $employeeModel->first_name . ' ' . $employeeModel->last_name;
            $user->email = $employeeModel->email;
            $user->save();
        }

        return redirect()->route('employee.index')->with('success', 'Data Berhasil Disimpan!');
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
}
