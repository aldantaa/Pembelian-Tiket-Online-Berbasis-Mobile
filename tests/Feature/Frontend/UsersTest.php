<?php

namespace Tests\Feature\Frontend;

use App\Models\User;
use Illuminate\Support\Collection;
use Tests\TestCase;

class UsersTest extends TestCase
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

    public function test_tampilan_daftar_pengguna_admin_dapat_dirender(): void
    {
        $this->loginAsAdmin();

        $user = (object) [
            'id' => 2,
            'name' => 'Budi Santoso',
            'email' => 'budi@example.test',
            'created_at' => '2026-11-01 10:00:00',
        ];

        $view = $this->view('admin.users', [
            'users' => new Collection([$user]),
            'editing' => null,
        ]);

        $view->assertSee('Data pengguna');
        $view->assertSee('Budi Santoso');
        $view->assertSee('budi@example.test');
        $view->assertSee('Terdaftar');
        $view->assertSee('Ubah');
        $view->assertSee('Hapus');
    }

    public function test_tampilan_daftar_pengguna_ketika_data_kosong(): void
    {
        $this->loginAsAdmin();

        $view = $this->view('admin.users', [
            'users' => new Collection(),
            'editing' => null,
        ]);

        $view->assertSee('Belum ada pengguna.');
    }

    public function test_form_edit_pengguna_dapat_dirender(): void
    {
        $this->loginAsAdmin();

        $editing = (object) [
            'id' => 2,
            'name' => 'Budi Santoso',
            'email' => 'budi@example.test',
            'created_at' => '2026-11-01 10:00:00',
        ];

        $view = $this->view('admin.users', [
            'users' => new Collection([$editing]),
            'editing' => $editing,
        ]);

        $view->assertSee('Ubah pengguna #2');
        $view->assertSee('Budi Santoso');
        $view->assertSee('budi@example.test');
        $view->assertSee('Simpan');
        $view->assertSee('Batal');
    }
}
