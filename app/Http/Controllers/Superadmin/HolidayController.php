<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

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
            return [
                'title' => $holiday->name,
                'start' => $holiday->date->format('Y-m-d'),
                'description' => $holiday->description,
                'color' => $holiday->color,
                'type' => 'holiday'
            ];
        });

        return response()->json($holidays);
    }

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
                        'description' => $holiday['description'] ?? 'Libur nasional',
                    ]
                );
            }

            return response()->json(['message' => 'Libur nasional berhasil disinkronkan']);
        } catch (\Exception $e) {
            Log::error('Error syncing national holidays: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal sinkronisasi libur'], 500);
        }
    }

    public function createEvent(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'date' => 'required|date',
            'color' => 'required|string',
        ]);

        Holiday::create([
            'name' => $request->name,
            'date' => $request->date,
            'description' => $request->description ?? null,
            'color' => $request->color,
        ]);

        return response()->json(['message' => 'Event berhasil ditambahkan']);
    }
}
