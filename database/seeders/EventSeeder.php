<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::truncate();
        $startDate = date('Y-01-01');
        $endDate = date('Y-12-31'); // Akhir tahun

        $events = []; // Inisialisasi array untuk menyimpan event

        // Loop dari awal tahun ke akhir tahun
        $currentDate = $startDate;
        while ($currentDate <= $endDate) {
            $dayOfWeek = date('w', strtotime($currentDate)); // Mengambil hari dalam format numerik (0 = Sunday)

            // Hanya untuk hari Minggu
            if ($dayOfWeek == 0) {
                // Tambahkan event
                $events[] = [
                    'title' => 'holiday', // Judul event
                    'start_date' => $currentDate,
                    'end_date' => $currentDate,
                    'category' => 'danger', // Kategori untuk hari Minggu
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Tambahkan satu hari
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }

        // Simpan atau perbarui event di database untuk menghindari duplikat
        foreach ($events as $eventData) {
            Event::updateOrCreate(
                [
                    'start_date' => $eventData['start_date'],
                    'title' => $eventData['title'],
                ],
                $eventData
            );
        }
    }
}
