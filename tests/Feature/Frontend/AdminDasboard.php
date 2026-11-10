<?php

namespace Tests\Feature\Frontend;

use App\Models\User;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function test_tampilan_dashboard_admin_dapat_dirender(): void
    {
        $admin = new User([
            'name' => 'Admin My Halte',
            'email' => 'admin@myhalte.test',
            'role' => 'admin',
        ]);
        $admin->id = 1;

        $this->actingAs($admin);
        $this->withViewErrors([]);

        $view = $this->view('admin.dashboard', [
            'period' => 'harian',
            'stats' => [
                'Total pengguna' => 10,
                'Total pemesanan' => 5,
                'Total pendapatan' => 'Rp 1.500.000',
            ],
            'chart' => [
                '01 Nov' => 500000,
                '02 Nov' => 1000000,
            ],
            'routes' => [
                [
                    'route' => 'Madiun - Surabaya',
                    'passengers' => 3,
                    'revenue' => 1500000,
                ],
            ],
        ]);

        $view->assertSee('Laporan & statistik');
        $view->assertSee('Total pengguna');
        $view->assertSee('Total pemesanan');
        $view->assertSee('Rp 1.500.000');
        $view->assertSee('Pendapatan harian');
        $view->assertSee('Pendapatan per rute');
        $view->assertSee('Madiun - Surabaya');
        $view->assertSee('Ekspor CSV');
        $view->assertSee('Kelola Jadwal');
        $view->assertSee('Kelola Pengguna');
    }

    public function test_dashboard_admin_mendukung_periode_bulanan(): void
    {
        $admin = new User([
            'name' => 'Admin My Halte',
            'email' => 'admin@myhalte.test',
            'role' => 'admin',
        ]);
        $admin->id = 1;

        $this->actingAs($admin);
        $this->withViewErrors([]);

        $view = $this->view('admin.dashboard', [
            'period' => 'bulanan',
            'stats' => [],
            'chart' => ['Nov 2026' => 0],
            'routes' => [],
        ]);

        $view->assertSee('Pendapatan bulanan');
        $view->assertSee('Harian');
        $view->assertSee('Bulanan');
    }
}
