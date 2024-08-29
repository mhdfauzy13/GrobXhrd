<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function showCalendar()
    {
        return view('superadmin.holiday.calendar');
    }

    public function getHolidays(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $client = new Client();
        $response = $client->get('https://api-harilibur.vercel.app/', [
            'query' => [
                'year' => $year,
                'month' => $month,
            ]
        ]);

        $holidays = json_decode($response->getBody()->getContents(), true);

        $events = array_map(function ($holiday) {
            return [
                'title' => $holiday['holiday_name'],
                'start' => $holiday['holiday_date'],
                'allDay' => true,
            ];
        }, $holidays);

        return response()->json($events);
    }
}