<?php

namespace Tests\Feature\Frontend;

use Tests\TestCase;
use App\Models\Schedule;
use App\Models\Seat;
use Illuminate\Support\ViewErrorBag;

class SeatTest extends TestCase
{
    public function test_tampilan_halaman_pilih_kursi_dapat_dirender(): void
    {
        // Data jadwal dummy
        $schedule = new Schedule([
            'kota_asal' => 'Jakarta',
            'kota_tujuan' => 'Surabaya',
            'waktu_keberangkatan' => now()->addDay(),
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
                'is_booked' => true,
            ]),
        ]);
        
        $this->withViewErrors([]);
        // Render tampilan Blade secara langsung
        $view = $this->view('booking.seats', [
            'schedule' => $schedule,
            'seats' => $seats,
            'totalSeats' => 2,
            'availableSeats' => 1,
        ]);

        // Memastikan elemen tampilan muncul
        $view->assertSee('A1');
        $view->assertSee('A2');
        $view->assertSee('Pilih Kursi');
        $view->assertSee('Jakarta');
        $view->assertSee('Surabaya');
    }
}