<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attandance;
use App\Models\Event;
use App\Models\Offrequest;
use Illuminate\Http\Request;

class DashboardEmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard.employee')->only(['index']);
    }

    // public function index()
    // {
    //     $offrequests = Offrequest::where('user_id', auth()->id()) // Ambil request milik user yang sedang login
    //         ->latest()
    //         ->limit(5)
    //         ->get();

    //     return view('employee.dashboard.index', compact('offrequests'));
    // }


    public function index()
    {
        // Ambil data attendance karyawan, misalnya yang terakhir kali hadir
        $attendances = Attandance::where('employee_id', auth()->user()->employee_id)
            ->take(5)
            // ->paginate(10);
            ->get();


        // Ambil data offrequest karyawan, misalnya yang terakhir diajukan
        $offrequests = Offrequest::where('user_id', auth()->user()->user_id)
            ->latest()  // Mengambil data terbaru
            ->take(5)  // Batasi hanya 5 data terakhir
            ->get();

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
