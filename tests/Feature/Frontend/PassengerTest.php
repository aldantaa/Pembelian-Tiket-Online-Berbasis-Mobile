<?php

namespace Tests\Feature\Frontend;

use Tests\TestCase;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Seat;

class PassengerTest extends TestCase
{
    public function test_tampilan_halaman_data_penumpang_dapat_dirender(): void
    {
        // User dummy untuk simulasi pengguna yang sudah login
        $user = new User([
            'name' => 'Tiara Maya Lestari',
            'email' => 'tiara@example.com',
            'phone' => '081234567890',
        ]);

        $user->id = 1;

        $this->actingAs($user);

        // Data jadwal dummy
        $schedule = new Schedule([
    'kota_asal' => 'Jakarta',
    'kota_tujuan' => 'Surabaya',
    'waktu_keberangkatan' => now()->setTime(8, 0),
    'waktu_tiba' => now()->setTime(16, 0),
    'harga' => 150000,
]);

$schedule->id = 1;

        // Data kursi dummy
        $seats = collect([
            new Seat([
                'seat_number' => 'A1',
                'is_booked' => false,
            ]),
            new Seat([
                'seat_number' => 'A2',
                'is_booked' => false,
            ]),
        ]);

        // Menyiapkan error bag untuk tampilan
        $this->withViewErrors([]);

        // Render tampilan Blade secara langsung
        $view = $this->view('booking.passenger', [
            'schedule' => $schedule,
            'seats' => $seats,
            'total' => 300000,
        ]);

        // Memastikan elemen halaman tampil
        $view->assertSee('Data penumpang');
        $view->assertSee('Penumpang kursi A1');
        $view->assertSee('Penumpang kursi A2');
        $view->assertSee('Nama lengkap');
        $view->assertSee('Nomor identitas');
        $view->assertSee('Nomor telepon');
        $view->assertSee('Lanjutkan');
        $view->assertSee('Tiara Maya Lestari');
    }
}