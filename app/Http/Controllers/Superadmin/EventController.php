<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return view('superadmin.event.index');
    }

    public function ListEvent(Request $request)
    {
        $start = date('Y-m-d', strtotime($request->start));
        $end = date('Y-m-d', strtotime($request->end));

        $events = Event::where('start_date', '>=', $start)
            ->where('end_date', '<=', $end)->get()
            ->map(fn($item) => [
                'id' => $item->events_id,
                'title' => $item->title,
                'start' => $item->start_date,
                'end' => $item->end_date,
                'category' => $item->category
            ]);

        return response()->json($events);
    }

    public function create(Event $event)
    {
        return view('superadmin.event-form', ['data' => $event, 'action' => route('events.store')]);
    }
}
