<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attandance;
use App\Models\Event;
use App\Models\Offrequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardEmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard.employee')->only(['index']);
    }

    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Ambil data attendance karyawan untuk bulan saat ini
        $attendances = Attandance::where('employee_id', auth()->user()->employee_id)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->paginate(10); // Batasi 10 data per halaman

        // Ambil data offrequest karyawan untuk bulan saat ini
        $offrequests = Offrequest::where('user_id', auth()->user()->user_id)
            ->whereMonth('start_event', $currentMonth)
            ->whereYear('start_event', $currentYear)
            ->paginate(10); // Batasi 10 data per halaman

        return view('Employee.dashboard.index', compact('attendances', 'offrequests'));
    }

    public function ListEvent(Request $request)
    {
        // Menangani filter berdasarkan tanggal
        $start = date('Y-m-d', strtotime($request->start));
        $end = date('Y-m-d', strtotime($request->end));

        // Ambil data event yang sudah dimasukkan oleh superadmin
        $events = Event::where('start_date', '>=', $start)->where('end_date', '<=', $end)->get()->map(
            fn($item) => [
                'event_id' => $item->event_id,
                'title' => $item->title,
                'start' => $item->start_date,
                'end' => date('Y-m-d', strtotime($item->end_date . '+1 days')),
                'category' => $item->category,
                'className' => ['bg-' . $item->category],
            ],
        );

        return response()->json($events);
    }
}
