<?php

namespace Tests\Feature\Frontend;

use Tests\TestCase;

class PaymentTest extends TestCase
{
    public function test_tampilan_halaman_pembayaran_dapat_dirender(): void
    {
        // Data dummy metode pembayaran
        $methods = [
            ['GoPay', 'E-Wallet'],
            ['Transfer Bank', 'Virtual Account'],
            ['QRIS', 'QR Code'],
        ];

        // Menyiapkan error bag
        $this->withViewErrors([]);

        // Render tampilan Blade secara langsung
        $view = $this->view('booking.payment', [
            'booking_code' => 'MH90801128',
            'methods' => $methods,
            'total' => 300000,
        ]);

        // Memastikan elemen halaman tampil
        $view->assertSee('Metode pembayaran');
        $view->assertSee('GoPay');
        $view->assertSee('Transfer Bank');
        $view->assertSee('QRIS');
        $view->assertSee('Total pembayaran');
        $view->assertSee('300.000');
        $view->assertSee('Bayar sekarang');
        $view->assertSee('Pilih metode pembayaran.');
        $view->assertSee('Lanjutkan ke halaman konfirmasi pembayaran.');
    }
}