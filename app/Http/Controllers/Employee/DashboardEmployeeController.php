<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Offrequest;
use Illuminate\Http\Request;

class DashboardEmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard.employee')->only(['index']);
    }

    public function index()
    {
        $offrequests = Offrequest::where('user_id', auth()->id())->get(); // Mengambil data cuti berdasarkan user yang login
        return view('employee.dashboard.index', compact('offrequests'));
    }
    

    public function ListEvent(Request $request)
    {
        // Menangani filter berdasarkan tanggal
        $start = date('Y-m-d', strtotime($request->start));
        $end = date('Y-m-d', strtotime($request->end));

        // Ambil data event yang sudah dimasukkan oleh superadmin
        $events = Event::where('start_date', '>=', $start)
            ->where('end_date', '<=', $end)
            ->get()
            ->map(fn($item) => [
                'event_id' => $item->event_id,
                'title' => $item->title,
                'start' => $item->start_date,
                'end' => date('Y-m-d', strtotime($item->end_date . '+1 days')),
                'category' => $item->category,
                'className' => ['bg-' . $item->category]
            ]);

        return response()->json($events);
    }


    public function show($id)
    {
        $offrequest = Offrequest::findOrFail($id);
        return view('employee.dashboard.index', compact('offrequest'));
    

    dd($id);


     }
}
