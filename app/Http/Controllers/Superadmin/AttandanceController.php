<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attandance;
use Illuminate\Support\Facades\Storage;

class AttandanceController extends Controller
{
     public function __construct()
    {
        // Menambahkan pengecekan permission untuk metode-metode tertentu
        $this->middleware('permission:attandance.index')->only('index');
        $this->middleware('permission:attandance.scanView')->only(['scanView']);
        $this->middleware('permission:attandance.scan')->only(['scan']);
    }
    public function index(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));

        $attendances = Attandance::with('employee')->whereDate('created_at', $date)->orderBy('created_at', 'desc')->get();

        return view('Superadmin.Employeedata.Attandance.index', compact('attendances', 'date'));
    }

    public function scanView()
    {
        return view('Employee.attandance.scan');
    }

    public function scan(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'image' => 'nullable|string',
        ]);

        $employee = Employee::where('employee_id', $request->employee_id)->first();
        $attendance = $employee->attendances()->orderBy('created_at', 'desc')->first();

        $imagePath = null;

        if ($request->has('image')) {
            $imageData = $request->input('image');
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageName = uniqid() . '.jpg';
            Storage::put('public/attandance_images/' . $imageName, base64_decode($imageData));
            $imagePath = 'attandance_images/' . $imageName;
        }

        if (!$attendance || $attendance->check_out) {
            // Check-in baru
            $newAttendance = Attandance::create([
                'employee_id' => $employee->employee_id,
                'check_in' => now(),
                'status' => 'IN',
                'image' => $imagePath,
            ]);

            return response()->json(['success' => true, 'message' => 'Employee checked IN', 'attendance' => $newAttendance]);
        } else {
            // Check-out
            $attendance->update([
                'check_out' => now(),
                'status' => 'OUT',
                'image' => $imagePath,
            ]);

            return response()->json(['success' => true, 'message' => 'Employee checked OUT', 'attendance' => $attendance]);
        }
    }
}
