<?php

namespace Tests\Feature\Frontend;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class TransactionsTest extends TestCase
{
    public function test_tampilan_riwayat_transaksi_admin_dapat_dirender(): void
    {
        $admin = new User([
            'name' => 'Admin My Halte',
            'email' => 'admin@myhalte.test',
            'role' => 'admin',
        ]);
        $admin->id = 1;

        $this->actingAs($admin);
        $this->withViewErrors([]);

        $transaction = (object) [
            'booking_code' => 'MH20261110001',
            'user_name' => 'Budi Santoso',
            'method' => 'QRIS',
            'amount' => 250000,
            'payment_status' => 'paid',
            'booking_status' => 'paid',
            'created_at' => '2026-11-10 09:30:00',
        ];

        $transactions = new LengthAwarePaginator(
            [$transaction],
            1,
            10,
            1,
            ['path' => '/admin/transactions']
        );

        $view = $this->view('admin.transactions', [
            'transactions' => $transactions,
        ]);

        $view->assertSee('Riwayat transaksi');
        $view->assertSee('MH20261110001');
        $view->assertSee('Budi Santoso');
        $view->assertSee('QRIS');
        $view->assertSee('250.000');
        $view->assertSee('Paid');
        $view->assertSee('Status Pembayaran');
    }

    public function test_tampilan_transaksi_ketika_data_kosong(): void
    {
        $admin = new User([
            'name' => 'Admin My Halte',
            'email' => 'admin@myhalte.test',
            'role' => 'admin',
        ]);
        $admin->id = 1;

        $this->actingAs($admin);
        $this->withViewErrors([]);

        $transactions = new LengthAwarePaginator(
            [],
            0,
            10,
            1,
            ['path' => '/admin/transactions']
        );

        $view = $this->view('admin.transactions', [
            'transactions' => $transactions,
        ]);

        $view->assertSee('Belum ada transaksi.');
    }
}
