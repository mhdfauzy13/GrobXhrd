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
            return [
                'title' => $holiday->name,
                'start' => $holiday->date->format('Y-m-d'), // Tanggal event
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
                'color' => 'required|string|max:7', // Validasi kode warna hex
            ]);

            Holiday::create([
                'name' => $request->name,
                'color' => $request->color,
                'date' => now()->toDateString(), // Set tanggal ke hari ini atau sesuai kebutuhan
            ]);

            return response()->json(['message' => 'Event berhasil ditambahkan']);
        } catch (\Exception $e) {
            Log::error('Error creating event: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal menambahkan event'], 500);
        }
    }

    public function syncNationalHolidays()
    {
        try {
            $client = new Client();
            $response = $client->get('https://holidayapi.com/v1/holidays', [
                'query' => [
                    'key' => 'HOLIDAY_API_KEY', // Gantilah dengan API key Anda yang benar
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
                        'color' => 'gray', // Set warna default jika tidak ada informasi warna dari API
                    ]
                );
            }

            return response()->json(['message' => 'Libur nasional berhasil disinkronkan']);
        } catch (\Exception $e) {
            Log::error('Error syncing national holidays: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal sinkronisasi libur nasional'], 500);
        }
    }
}
