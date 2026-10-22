<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat atau perbarui akun admin.
        $password = env('ADMIN_SEED_PASSWORD');

        if (empty($password)) {
            throw new RuntimeException(
                'ADMIN_SEED_PASSWORD belum diatur di file .env'
            );
        }

        User::updateOrCreate(
            ['email' => env('ADMIN_SEED_EMAIL', 'admin@myhalte.test')],
            [
                'name' => 'Administrator',
                'phone' => null,
                'password' => Hash::make($password),
                'role' => 'admin',
            ]
        );

        // Data jadwal bus.
        $schedules = [
            [
                'nama_bus' => 'Kirana Express',
                'kota_asal' => 'Madiun',
                'kota_tujuan' => 'Surabaya',
                'waktu_keberangkatan' => '2026-11-11 08:00:00',
                'harga' => 75000,
            ],
            [
                'nama_bus' => 'Arunika Trans',
                'kota_asal' => 'Madiun',
                'kota_tujuan' => 'Solo',
                'waktu_keberangkatan' => '2026-11-11 13:30:00',
                'harga' => 85000,
            ],
            [
                'nama_bus' => 'Halte Prima',
                'kota_asal' => 'Madiun',
                'kota_tujuan' => 'Ngawi',
                'waktu_keberangkatan' => '2026-11-11 19:00:00',
                'harga' => 90000,
            ],
            [
                'nama_bus' => 'Swift Java',
                'kota_asal' => 'Madiun',
                'kota_tujuan' => 'Sragen',
                'waktu_keberangkatan' => '2026-11-11 10:00:00',
                'harga' => 250000,
            ],
        ];

        foreach ($schedules as $data) {
            $schedule = Schedule::updateOrCreate(
                [
                    'kota_asal' => $data['kota_asal'],
                    'kota_tujuan' => $data['kota_tujuan'],
                    'waktu_keberangkatan' => $data['waktu_keberangkatan'],
                ],
                [
                    'nama_bus' => $data['nama_bus'],
                    'harga' => $data['harga'],
                    'waktu_tiba' => null,
                    'kapasitas' => 40,
                    'kursi_tersedia' => 40,
                    'fasilitas' => 'AC',
                    'status' => 'scheduled',
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
