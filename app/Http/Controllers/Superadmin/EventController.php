<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
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
                'event_id' => $item->event_id,
                'title' => $item->title,
                'start' => $item->start_date,
                'end' => date('Y-m-d', strtotime($item->end_date . '+1 days')),
                'category' => $item->category,
                'className' => ['bg-' . $item->category]
            ]);

        return response()->json($events);
    }


    public function create(Event $event)
    {
        return view('superadmin.event-form', ['data' => $event, 'action' => route('events.store')]);
    }


    public function store(EventRequest $request, Event $event)
    {
        return $this->update($request, $event);
    }

    public function edit(Event $event)
    {
        return view('superadmin.event-form', [
            'data' => $event,
            'action' => route('events.update', $event->event_id)
        ]);
    }

    public function update(EventRequest $request, Event $event)
    {
        if ($request->has('delete')) {
            return $this->destroy($event);
        }

        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->title = $request->title;
        $event->category = $request->category;

        $event->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Save data successfully'
        ]);
    }


    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Delete data successful'
        ]);
    }
}
