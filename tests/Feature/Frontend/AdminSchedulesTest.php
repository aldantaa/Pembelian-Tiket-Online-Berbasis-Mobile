<?php

namespace Tests\Feature\Frontend;

use App\Models\User;
use Tests\TestCase;

class AdminSchedulesTest extends TestCase
{
    private function loginAsAdmin(): void
    {
        $admin = new User([
            'name' => 'Admin My Halte',
            'email' => 'admin@myhalte.test',
            'role' => 'admin',
        ]);
        $admin->id = 1;

        $this->actingAs($admin);
        $this->withViewErrors([]);
    }

    public function test_tampilan_kelola_jadwal_admin_dapat_dirender(): void
    {
        $this->loginAsAdmin();

        $view = $this->view('admin.schedules', [
            'editing' => null,
            'schedules' => [
                [
                    'id' => 1,
                    'bus' => 'Sugeng Rahayu',
                    'from' => 'Madiun',
                    'to' => 'Surabaya',
                    'depart' => '2026-11-12 08:00:00',
                    'arrive' => '2026-11-12 11:00:00',
                    'price' => 150000,
                ],
            ],
        ]);

        $view->assertSee('Kelola jadwal');
        $view->assertSee('Tambah jadwal');
        $view->assertSee('Sugeng Rahayu');
        $view->assertSee('Madiun');
        $view->assertSee('Surabaya');
        $view->assertSee('150.000');
        $view->assertSee('Berangkat');
        $view->assertSee('Tiba');
        $view->assertSee('Ubah');
        $view->assertSee('Hapus');
    }

    public function test_form_edit_jadwal_admin_dapat_dirender(): void
    {
        $this->loginAsAdmin();

        $schedule = [
            'id' => 1,
            'bus' => 'Sugeng Rahayu',
            'from' => 'Madiun',
            'to' => 'Surabaya',
            'depart' => '2026-11-12 08:00:00',
            'arrive' => '2026-11-12 11:00:00',
            'price' => 150000,
        ];

        $view = $this->view('admin.schedules', [
            'editing' => $schedule,
            'schedules' => [$schedule],
        ]);

        $view->assertSee('Ubah jadwal #1');
        $view->assertSee('Simpan perubahan');
        $view->assertSee('Batal');
    }
}
