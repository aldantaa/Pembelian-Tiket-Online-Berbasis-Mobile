<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_logout_invalidates_old_session(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withSession([
                'logout_test_marker' => 'session-lama',
            ])
            ->post(route('logout'))
            ->assertRedirect(route('login'))
            ->assertSessionMissing('logout_test_marker');

        $this->assertGuest();
    }

    public function test_user_cannot_access_protected_page_after_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->get(route('orders.index'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_guest_cannot_use_protected_order_page(): void
    {
        $this->get(route('orders.index'))
            ->assertRedirect(route('login'));
    }
}
