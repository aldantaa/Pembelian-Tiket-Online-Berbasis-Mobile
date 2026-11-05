<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private function createBooking(): array
    {
        $user = User::factory()->create();

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

        $booking = Booking::create([
            'booking_code' => 'BKTEST001',
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'seat_id' => $seat->id,
            'price' => 75000,
            'status' => 'pending',
            'expires_at' => now()->addMinutes(30),
        ]);

        return compact('user', 'schedule', 'seat', 'booking');
    }

    public function test_user_can_open_payment_page(): void
    {
        $data = $this->createBooking();

        $response = $this->actingAs($data['user'])
            ->get(route('payment.show', 'BKTEST001'));

        $response->assertStatus(200);
    }

    public function test_user_can_choose_payment_method(): void
    {
        $data = $this->createBooking();

        $response = $this->actingAs($data['user'])
            ->post(route('payment.pay', 'BKTEST001'), [
                'method' => 'qris',
            ]);

        $response->assertRedirect(route('payment.show', 'BKTEST001'));

        $this->assertDatabaseHas('payments', [
            'booking_code' => 'BKTEST001',
            'user_id' => $data['user']->id,
            'method' => 'qris',
            'amount' => 75000,
            'status' => 'pending',
        ]);
    }

    public function test_payment_method_must_be_valid(): void
    {
        $data = $this->createBooking();

        $response = $this->actingAs($data['user'])
            ->post(route('payment.pay', 'BKTEST001'), [
                'method' => 'metode_tidak_valid',
            ]);

        $response->assertSessionHasErrors('method');
    }

    public function test_user_cannot_access_another_users_payment(): void
    {
        $this->createBooking();

        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)
            ->get(route('payment.show', 'BKTEST001'));

        $response->assertNotFound();
    }

    public function test_successful_payment_creates_ticket(): void
    {
        $data = $this->createBooking();

        $payment = Payment::create([
            'booking_code' => 'BKTEST001',
            'user_id' => $data['user']->id,
            'method' => 'qris',
            'amount' => 75000,
            'status' => 'pending',
            'reference' => 'PAYTEST001',
        ]);

        $payment->markAsPaid();

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'success',
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $data['booking']->id,
            'status' => 'paid',
        ]);

        $this->assertDatabaseHas('tickets', [
            'booking_id' => $data['booking']->id,
            'payment_id' => $payment->id,
        ]);

        $this->assertDatabaseHas('alerts', [
            'user_id' => $data['user']->id,
            'booking_code' => 'BKTEST001',
        ]);
    }

    public function test_repeated_payment_does_not_create_duplicate_tickets(): void
    {
        $data = $this->createBooking();

        $payment = Payment::create([
            'booking_code' => 'BKTEST001',
            'user_id' => $data['user']->id,
            'method' => 'qris',
            'amount' => 75000,
            'status' => 'pending',
            'reference' => 'PAYTEST002',
        ]);

        $payment->markAsPaid();
        $payment->markAsPaid();

        $this->assertDatabaseCount('tickets', 1);
    }

    public function test_failed_payment_cancels_booking_and_releases_seat(): void
    {
        $data = $this->createBooking();

        $payment = Payment::create([
            'booking_code' => 'BKTEST001',
            'user_id' => $data['user']->id,
            'method' => 'qris',
            'amount' => 75000,
            'status' => 'pending',
            'reference' => 'PAYTEST003',
        ]);

        $payment->markAsFailed('failed');

        $this->assertDatabaseHas('bookings', [
            'id' => $data['booking']->id,
            'status' => 'cancelled',
        ]);

        $this->assertDatabaseHas('seats', [
            'id' => $data['seat']->id,
            'is_booked' => false,
        ]);
    }

    public function test_expired_payment_expires_booking_and_releases_seat(): void
    {
        $data = $this->createBooking();

        $payment = Payment::create([
            'booking_code' => 'BKTEST001',
            'user_id' => $data['user']->id,
            'method' => 'qris',
            'amount' => 75000,
            'status' => 'pending',
            'reference' => 'PAYTEST004',
        ]);

        $payment->markAsFailed('expired');

        $this->assertDatabaseHas('bookings', [
            'id' => $data['booking']->id,
            'status' => 'expired',
        ]);

        $this->assertDatabaseHas('seats', [
            'id' => $data['seat']->id,
            'is_booked' => false,
        ]);
    }
}
