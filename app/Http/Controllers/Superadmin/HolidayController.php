<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class HolidayController extends Controller
{

    public function index()
    {
        return view('superadmin.holiday.calendar');
    }


    public function calendar()
    {
        return view('superadmin.holiday.calendar');
    }

    public function data()
    {

        $holidays = Holiday::all()->map(function ($holiday) {

            $date = $holiday->date instanceof \Carbon\Carbon ? $holiday->date->format('Y-m-d') : $holiday->date;

            return [
                'title' => $holiday->name,
                'start' => $date,
                'color' => $holiday->color,
                'type' => 'holiday'
            ];
        });


        return response()->json($holidays);
    }


    public function createEvent(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'color' => 'required|string|max:7',
                'date' => 'nullable|date',
            ]);


            $date = $request->input('date', now()->toDateString());


            $event = Holiday::create([
                'name' => $request->name,
                'color' => $request->color,
                'date' => $date,
            ]);

            return response()->json([
                'message' => 'Event berhasil ditambahkan',
                'event' => $event,
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating event: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal menambahkan event'], 500);
        }
    }

    // Sinkronisasi libur nasional dari API
    public function syncNationalHolidays()
    {
        try {

            $client = new Client();
            $response = $client->get('https://holidayapi.com/v1/holidays', [
                'query' => [
                    'key' => 'HOLIDAY_API_KEY',
                    'country' => 'ID',
                    'year' => now()->year,
                    'public' => 'true',
                ]
            ]);


            $holidays = json_decode($response->getBody()->getContents(), true)['holidays'];


            foreach ($holidays as $holiday) {
                Holiday::updateOrCreate(
                    ['date' => $holiday['date']],
                    [
                        'name' => $holiday['name'],
                        'color' => 'gray',
                    ]
                );
            }

            return response()->json(['message' => 'Libur nasional berhasil disinkronkan']);
        } catch (\Exception $e) {
            Log::error('Error syncing national holidays: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal sinkronisasi libur nasional'], 500);
        }
    }


    public function saveEvent(Request $request)
    {

        $validated = $request->validate([
            'id' => 'required|integer',
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date',
            'color' => 'required|string|max:7'
        ]);

        $event = Holiday::find($validated['id']);
        if ($event) {

            $event->name = $validated['title'];
            $event->date = $validated['start'];
            $event->end_event = $validated['end'];
            $event->color = $validated['color'];
            $event->save();

            return response()->json(['success' => true, 'event' => $event]);
        }

        return response()->json(['success' => false, 'message' => 'Event not found'], 404);
    }
}
