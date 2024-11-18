<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Overtime;
use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    public function __construct()
    {
        // Menggunakan middleware untuk validasi hak akses
        $this->middleware('permission:overtime.create')->only(['index', 'create', 'store', 'searchEmployees']);
    }

    public function index()
    {
        // Mengambil data overtime beserta relasi ke model Employee
        $overtimes = Overtime::with('employee')->paginate(10); // Mengambil data overtime dengan pagination

        return view('superadmin.overtime.index', compact('overtimes'));
    }

    public function create()
    {
        // Ambil data employee, urutkan berdasarkan nama
        $employees = Employee::select('employee_id', 'first_name', 'last_name')->orderBy('first_name')->get();
        return view('superadmin.overtime.create', compact('employees'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,employee_id', // Pastikan employee_id ada di tabel employees
            'overtime_date' => 'required|date',
            'duration' => 'required|integer|min:1|max:8', // Durasi overtime harus antara 1-8 jam
            'notes' => 'nullable|string', // Catatan tidak wajib
        ]);

        // Simpan data overtime
        Overtime::create([
            'employee_id' => $validated['employee_id'],
            'overtime_date' => $validated['overtime_date'],
            'duration' => $validated['duration'],
            'notes' => $validated['notes'], // Menyimpan catatan overtime jika ada
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('overtime.index')->with('success', 'Overtime berhasil ditambahkan.');
    }

    public function searchEmployees(Request $request)
    {
        // Fungsi untuk mencari employee berdasarkan nama depan atau nama belakang
        $query = $request->get('query');
        $employees = Employee::where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orderBy('first_name')  // Mengurutkan hasil berdasarkan first_name
            ->get(['employee_id', 'first_name', 'last_name']); // Ambil kolom yang dibutuhkan

        // Gabungkan nama depan dan nama belakang sebagai nama lengkap
        $employees = $employees->map(function ($employee) {
            $employee->full_name = $employee->first_name . ' ' . $employee->last_name;
            return $employee;
        });

        return response()->json($employees);
    }
}
