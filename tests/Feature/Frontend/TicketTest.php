<?php

namespace Tests\Feature\Frontend;

use Tests\TestCase;
use App\Models\User;

class TicketTest extends TestCase
{
    public function test_tampilan_halaman_e_tiket_dapat_dirender(): void
    {
        // User dummy
        $user = new User([
            'name' => 'Tiara Maya Lestari',
            'email' => 'tiara@example.com',
        ]);

        $user->id = 1;

        $this->actingAs($user);

        // Data dummy tiket
        $bus = [
            'name' => 'Bus Executive',
            'class' => 'Executive',
            'depart' => '08:00',
            'arrive' => '16:00',
        ];

        $query = [
            'from' => 'Jakarta',
            'to' => 'Surabaya',
            'date' => '2026-11-10',
        ];

        $seats = ['A1', 'A2'];

        $this->withViewErrors([]);

        // Render tampilan E-Tiket
        $view = $this->view('tickets.show', [
            'bus' => $bus,
            'query' => $query,
            'seats' => $seats,
            'code' => 'MH90801128',
            'status' => 'pending',
            'total' => 300000,
        ]);

        // Memeriksa elemen tampilan
        $view->assertSee('Bus Executive');
        $view->assertSee('Executive');
        $view->assertSee('Jakarta');
        $view->assertSee('Surabaya');
        $view->assertSee('Tiara Maya Lestari');
        $view->assertSee('Nomor kursi');
        $view->assertSee('A1, A2');
        $view->assertSee('MH90801128');
        $view->assertSee('Status pembayaran');
        $view->assertSee('300.000');
        $view->assertSee('QR code tersedia setelah pembayaran berhasil.');
        $view->assertSee('Lihat Status Transaksi');
        $view->assertSee('Lihat Riwayat');
        $view->assertSee('Kembali ke Beranda');
    }
}