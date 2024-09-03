<?php
namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
use App\Models\OffRequest;

class HolidayController extends Controller
{
    public function index()
    {
        return view('superadmin.holiday.calendar');
    }

    public function data()
    {
        // Mengambil data libur dan memformatnya untuk FullCalendar
        $holidays = Holiday::all()->map(function($holiday) {
            return [
                'title' => $holiday->name,
                'start' => $holiday->date->format('Y-m-d'),
                'description' => $holiday->description,
                'type' => 'holiday' // Menambahkan tipe event sebagai 'holiday'
            ];
        });

        // Mengambil data cuti dan memformatnya untuk FullCalendar
        $leaves = OffRequest::all()->map(function($leave) {
            return [
                'title' => $leave->name,
                'start' => $leave->start_date->format('Y-m-d'),
                'end' => $leave->end_date->format('Y-m-d'),
                'description' => $leave->description,
                'type' => 'leave' // Menambahkan tipe event sebagai 'leave'
            ];
        });

        // Menggabungkan data libur dan cuti
        $events = $holidays->merge($leaves);

        return response()->json($events);
    }
}