<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attandance;

class AttandanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));

        $attendances = Attandance::with('employee')->whereDate('created_at', $date)->orderBy('created_at', 'desc')->get();

        return view('Superadmin.Employeedata.Attandance.index', compact('attendances', 'date'));
    }

    public function scanView()
    {
        return view('Superadmin.Employeedata.Attandance.scan');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
        ]);

        $employee = Employee::where('employee_id', $request->employee_id)->first();
        $attendance = $employee->attendances()->orderBy('created_at', 'desc')->first();

        if ($attendance && $attendance->check_out == null) {
            // Check-Out
            $attendance->update([
                'check_out' => now(),
                'status' => 'OUT',
            ]);
            $message = "{$employee->name} has successfully checked OUT!";
        } else {
            // Check-In
            $employee->attendances()->create([
                'check_in' => now(),
                'status' => 'IN',
            ]);
            $message = "{$employee->name} has successfully checked IN!";
        }

        return response()->json(['message' => $message]);
    }
}
