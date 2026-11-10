<?php

namespace Tests\Feature\Frontend;

use Tests\TestCase;
use App\Models\Schedule;

class TicketsIndexTest extends TestCase
{
    public function test_tampilan_daftar_tiket_dapat_dirender(): void
    {
        // Jadwal dummy
        $schedule = new Schedule([
            'kota_asal' => 'Jakarta',
            'kota_tujuan' => 'Surabaya',
            'waktu_keberangkatan' => now()->setTime(8, 0),
        ]);

        $schedule->id = 1;

        // Data tiket dummy
        $tickets = [
            [
                'schedule' => $schedule,
                'seats' => collect(['A1', 'A2']),
                'journey_status' => 'Perjalanan akan datang',
                'status' => 'paid',
                'booking_code' => 'MH90801128',
                'total' => 300000,
            ],
        ];

        $this->withViewErrors([]);

        // Render tampilan daftar tiket
        $view = $this->view('tickets.index', [
            'tickets' => $tickets,
        ]);

        // Memeriksa elemen tampilan
        $view->assertSee('Tiket Saya');
        $view->assertSee('Jakarta');
        $view->assertSee('Surabaya');
        $view->assertSee('A1, A2');
        $view->assertSee('Perjalanan akan datang');
        $view->assertSee('MH90801128');
        $view->assertSee('Paid');
        $view->assertSee('300.000');
    }

    public function test_tampilan_ketika_belum_memiliki_tiket(): void
    {
        $this->withViewErrors([]);

        // Render halaman dengan daftar tiket kosong
        $view = $this->view('tickets.index', [
            'tickets' => [],
        ]);

        $view->assertSee('Tiket Saya');
        $view->assertSee('Belum ada tiket.');
        $view->assertSee('Cari Jadwal Bus');
    }
}