<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    // KF-02.1: Pengguna yang login dapat membuka halaman profil.
    public function test_authenticated_user_can_open_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        $response->assertViewIs('auth.profile');
        $response->assertViewHas('user');
    }

    // KF-02.2: Pengguna dapat memperbarui nama.
    public function test_user_can_update_name(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Lama',
        ]);

        $response = $this->actingAs($user)->post('/profile', [
            'name' => 'Nama Baru',
            'email' => $user->email,
            'phone' => $user->phone,
        ]);

        $response->assertRedirect('/profile');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru',
        ]);
    }

    // KF-02.3: Pengguna dapat memperbarui email.
    public function test_user_can_update_email(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/profile', [
            'name' => $user->name,
            'email' => 'emailbaru@example.com',
            'phone' => $user->phone,
        ]);

        $response->assertRedirect('/profile');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'emailbaru@example.com',
        ]);
    }

    // KF-02.4: Pengguna dapat memperbarui nomor telepon.
    public function test_user_can_update_phone(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '089876543210',
        ]);

        $response->assertRedirect('/profile');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'phone' => '089876543210',
        ]);
    }

    // KF-02.5: Nomor telepon boleh dikosongkan.
    public function test_user_can_update_profile_with_empty_phone(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '',
        ]);

        $response->assertRedirect('/profile');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'phone' => null,
        ]);
    }

    // KF-02.6: Email milik pengguna lain tidak boleh digunakan.
    public function test_user_cannot_use_another_users_email(): void
    {
        $user = User::factory()->create();

        $anotherUser = User::factory()->create([
            'email' => 'oranglain@example.com',
        ]);

        $response = $this->actingAs($user)->post('/profile', [
            'name' => $user->name,
            'email' => $anotherUser->email,
            'phone' => $user->phone,
        ]);

        $response->assertSessionHasErrors('email');
    }

    // KF-02.7: Nomor telepon milik pengguna lain tidak boleh digunakan.
    public function test_user_cannot_use_another_users_phone(): void
    {
        $user = User::factory()->create();

        $anotherUser = User::factory()->create([
            'phone' => '081234567899',
        ]);

        $response = $this->actingAs($user)->post('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $anotherUser->phone,
        ]);

        $response->assertSessionHasErrors('phone');
    }

    // KF-02.8: Pengguna yang belum login tidak dapat membuka profil.
    public function test_guest_cannot_open_profile(): void
    {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    }

    // KF-02.9: Pengguna yang belum login tidak dapat memperbarui profil.
    public function test_guest_cannot_update_profile(): void
    {
        $response = $this->post('/profile', [
            'name' => 'Nama Baru',
            'email' => 'baru@example.com',
            'phone' => '081234567890',
        ]);

        $response->assertRedirect('/login');
    }
}
