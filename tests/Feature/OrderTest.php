<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private function createBooking(User $user, string $code, string $status = 'paid'): Booking
    {
        $schedule = Schedule::create([
            'nama_bus' => 'Bus Test',
            'kota_asal' => 'Madiun',
            'kota_tujuan' => 'Surabaya',
            'waktu_keberangkatan' => now()->addDay(),
            'waktu_tiba' => now()->addDay()->addHours(3),
            'harga' => 75000,
        ]);

        $seat = Seat::create([
            'schedule_id' => $schedule->id,
            'seat_number' => 'A1',
            'is_booked' => true,
        ]);

        return Booking::create([
            'booking_code' => $code,
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'seat_id' => $seat->id,
            'price' => 75000,
            'status' => $status,
            'expires_at' => now()->addMinutes(30),
        ]);
    }

    public function test_user_can_see_paid_booking_in_order_history(): void
    {
        $user = User::factory()->create();

        $booking = $this->createBooking($user, 'ORDTEST01');

        $this->actingAs($user)
            ->get(route('orders.index'))
            ->assertOk()
            ->assertViewIs('tickets.index')
            ->assertSee('ORDTEST01')
            ->assertSee('Madiun')
            ->assertSee('Surabaya');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_order_history_only_shows_logged_in_users_bookings(): void
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $this->createBooking($user, 'MYORDER01');
        $this->createBooking($anotherUser, 'OTHERORDER01');

        $this->actingAs($user)
            ->get(route('orders.index'))
            ->assertOk()
            ->assertSee('MYORDER01')
            ->assertDontSee('OTHERORDER01');
    }

    public function test_user_can_open_own_order_detail(): void
    {
        $user = User::factory()->create();

        $this->createBooking($user, 'DETAILTEST01');

        $this->actingAs($user)
            ->get(route('orders.show', 'DETAILTEST01'))
            ->assertOk()
            ->assertViewIs('tickets.show')
            ->assertSee('DETAILTEST01')
            ->assertSee('Madiun')
            ->assertSee('Surabaya');
    }

    public function test_user_cannot_open_another_users_order_detail(): void
    {
        $owner = User::factory()->create();
        $anotherUser = User::factory()->create();

        $this->createBooking($owner, 'PRIVATEORDER01');

        $this->actingAs($anotherUser)
            ->get(route('orders.show', 'PRIVATEORDER01'))
            ->assertNotFound();
    }

    public function test_guest_cannot_access_order_history(): void
    {
        $this->get(route('orders.index'))
            ->assertRedirect(route('login'));
    }

    public function test_issued_ticket_appears_in_order_history_after_successful_payment(): void
    {
        $user = User::factory()->create();

        $booking = $this->createBooking(
            $user,
            'ISSUEDTICKET01',
            'pending'
        );

        $payment = Payment::create([
            'booking_code' => 'ISSUEDTICKET01',
            'user_id' => $user->id,
            'method' => 'transfer_bank',
            'amount' => 75000,
            'status' => 'pending',
            'reference' => 'PAYORDERTEST01',
        ]);

        $payment->markAsPaid();

        $this->assertDatabaseHas('tickets', [
            'booking_id' => $booking->id,
            'payment_id' => $payment->id,
        ]);

        $this->actingAs($user)
            ->get(route('orders.index'))
            ->assertOk()
            ->assertViewIs('tickets.index')
            ->assertSee('ISSUEDTICKET01');
    }

}
