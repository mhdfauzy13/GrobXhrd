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
        $fake = fake('idID'); // Menggunakan Faker instance
        $startDate = date('Y-01-01'); // Awal tahun
        $endDate = date('Y-12-31'); // Akhir tahun

        $events = []; // Inisialisasi array untuk menyimpan event

        // Loop dari awal tahun ke akhir tahun
        $currentDate = $startDate;
        while ($currentDate <= $endDate) {
            $dayOfWeek = date('w', strtotime($currentDate)); // Mengambil hari dalam format numerik (0 = Sunday, 6 = Saturday)

            // Hanya untuk hari Minggu
            if ($dayOfWeek == 0) {

                $existingEvent = array_filter($events, function ($event) use ($currentDate) {
                    return $event['start_date'] === $currentDate;
                });

                // Jika belum ada, tambahkan event
                if (empty($existingEvent)) {
                    $events[] = [
                        'title' => 'holiday', // Judul event
                        'start_date' => $currentDate,
                        'end_date' => $currentDate,
                        'category' => 'danger', // Kategori untuk hari Minggu
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Tambahkan satu hari
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }

        // Hanya simpan event yang unik
        Event::insert($events); // Menyimpan semua event ke database
    }
}
