<?php

namespace Tests\Feature;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlertTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_their_own_alerts(): void
    {
        $user = User::factory()->create();

        Alert::create([
            'user_id' => $user->id,
            'type' => 'payment',
            'title' => 'Pembayaran berhasil',
            'message' => 'Pembayaran berhasil dan tiket sudah terbit.',
        ]);

        $this->actingAs($user);

        $response = $this->getJson('/alerts');

        $response->assertOk()
            ->assertJsonPath('unread_count', 1)
            ->assertJsonPath('data.data.0.title', 'Pembayaran berhasil');
    }

    public function test_user_cannot_view_another_users_alerts(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Alert::create([
            'user_id' => $otherUser->id,
            'type' => 'payment',
            'title' => 'Notifikasi pengguna lain',
            'message' => 'Ini bukan notifikasi milik pengguna yang login.',
        ]);

        $this->actingAs($user);

        $response = $this->getJson('/alerts');

        $response->assertOk()
            ->assertJsonPath('unread_count', 0)
            ->assertJsonMissing([
                'title' => 'Notifikasi pengguna lain',
            ]);
    }

    public function test_user_can_mark_their_alert_as_read(): void
    {
        $user = User::factory()->create();

        $alert = Alert::create([
            'user_id' => $user->id,
            'type' => 'payment',
            'title' => 'Pembayaran berhasil',
            'message' => 'Pembayaran berhasil.',
        ]);

        $this->actingAs($user);

        $response = $this->patchJson("/alerts/{$alert->id}/read");

        $response->assertOk();

        $this->assertDatabaseMissing('alerts', [
            'id' => $alert->id,
            'read_at' => null,
        ]);
    }

    public function test_user_cannot_mark_another_users_alert_as_read(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $alert = Alert::create([
            'user_id' => $otherUser->id,
            'type' => 'payment',
            'title' => 'Notifikasi pengguna lain',
            'message' => 'Notifikasi ini milik pengguna lain.',
        ]);

        $this->actingAs($user);

        $response = $this->patchJson("/alerts/{$alert->id}/read");

        $response->assertNotFound();

        $this->assertDatabaseHas('alerts', [
            'id' => $alert->id,
            'read_at' => null,
        ]);
    }

    public function test_user_can_mark_all_their_alerts_as_read(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        foreach (range(1, 3) as $number) {
            Alert::create([
                'user_id' => $user->id,
                'type' => 'payment',
                'title' => "Notifikasi {$number}",
                'message' => "Pesan notifikasi {$number}.",
            ]);
        }

        $otherAlert = Alert::create([
            'user_id' => $otherUser->id,
            'type' => 'payment',
            'title' => 'Notifikasi pengguna lain',
            'message' => 'Notifikasi ini tidak boleh berubah.',
        ]);

        $this->actingAs($user);

        $response = $this->patchJson('/alerts/read-all');

        $response->assertOk()
            ->assertJsonPath('updated_count', 3);

        $this->assertSame(
            0,
            Alert::where('user_id', $user->id)
                ->whereNull('read_at')
                ->count()
        );

        $this->assertDatabaseHas('alerts', [
            'id' => $otherAlert->id,
            'read_at' => null,
        ]);
    }
}
