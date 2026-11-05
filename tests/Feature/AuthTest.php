<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // KF-01.1: Halaman registrasi dapat dibuka.
    public function test_registration_page_can_be_opened(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    // KF-01.2: Pengguna dapat melakukan registrasi.
    public function test_user_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Pengguna Test',
            'email' => 'pengguna@example.com',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseHas('users', [
            'name' => 'Pengguna Test',
            'email' => 'pengguna@example.com',
            'phone' => '081234567890',
            'role' => 'user',
        ]);
    }

    // KF-01.3: Email yang sudah digunakan tidak dapat didaftarkan kembali.
    public function test_registration_rejects_duplicate_email(): void
    {
        User::factory()->create([
            'email' => 'pengguna@example.com',
            'phone' => '081111111111',
        ]);

        $response = $this->post('/register', [
            'name' => 'Pengguna Kedua',
            'email' => 'pengguna@example.com',
            'phone' => '082222222222',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    // KF-01.4: Nomor telepon yang sudah digunakan tidak dapat didaftarkan kembali.
    public function test_registration_rejects_duplicate_phone(): void
    {
        User::factory()->create([
            'email' => 'pertama@example.com',
            'phone' => '081234567890',
        ]);

        $response = $this->post('/register', [
            'name' => 'Pengguna Kedua',
            'email' => 'kedua@example.com',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('phone');
    }

    // KF-01.5: Pengguna dapat login menggunakan email.
    public function test_user_can_login_using_email(): void
    {
        $user = User::factory()->create([
            'email' => 'pengguna@example.com',
            'phone' => '081234567890',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        $response = $this->post('/login', [
            'login' => 'pengguna@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/schedules');
        $this->assertAuthenticatedAs($user);
    }

    // KF-01.6: Pengguna dapat login menggunakan nomor telepon.
    public function test_user_can_login_using_phone(): void
    {
        $user = User::factory()->create([
            'email' => 'pengguna@example.com',
            'phone' => '081234567890',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        $response = $this->post('/login', [
            'login' => '081234567890',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/schedules');
        $this->assertAuthenticatedAs($user);
    }

    // KF-01.7: Login ditolak jika password salah.
    public function test_login_rejects_wrong_password(): void
    {
        User::factory()->create([
            'email' => 'pengguna@example.com',
            'phone' => '081234567890',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'login' => 'pengguna@example.com',
            'password' => 'password-salah',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    // KF-01.8: Pengguna dapat logout.
    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect();
        $this->assertGuest();
    }
}
