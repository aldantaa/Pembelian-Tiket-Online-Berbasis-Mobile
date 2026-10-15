<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Seat;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            [
                'kota_asal' => 'Madiun',
                'kota_tujuan' => 'Surabaya',
                'waktu_keberangkatan' => '2026-11-11 08:00:00',
                'harga' => 75000,
            ],
            [
                'kota_asal' => 'Madiun',
                'kota_tujuan' => 'Solo',
                'waktu_keberangkatan' => '2026-11-11 13:30:00',
                'harga' => 85000,
            ],
            [
                'kota_asal' => 'Madiun',
                'kota_tujuan' => 'Ngawi',
                'waktu_keberangkatan' => '2026-11-11 19:00:00',
                'harga' => 90000,
            ],
            [
                'kota_asal' => 'Madiun',
                'kota_tujuan' => 'Sragen',
                'waktu_keberangkatan' => '2026-11-11 10:00:00',
                'harga' => 250000,
            ],
        ];

        foreach ($schedules as $data) {
            $schedule = Schedule::firstOrCreate(
                [
                    'kota_asal' => $data['kota_asal'],
                    'kota_tujuan' => $data['kota_tujuan'],
                    'waktu_keberangkatan' =>
                        $data['waktu_keberangkatan'],
                ],
                [
                    'harga' => $data['harga'],
                ]
            );

            foreach (range('A', 'J') as $row) {
                foreach (range(1, 4) as $number) {
                    Seat::firstOrCreate(
                        [
                            'schedule_id' => $schedule->id,
                            'seat_number' => $row . $number,
                        ],
                        [
                            'is_booked' => false,
                        ]
                    );
                }
            }
        }
    }
}
