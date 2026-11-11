<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    private function createPaidBooking(): array
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
            'booking_code' => 'BOOKTEST01',
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'seat_id' => $seat->id,
            'price' => 75000,
            'status' => 'pending',
            'expires_at' => now()->addMinutes(30),
        ]);

        Passenger::create([
            'booking_id' => $booking->id,
            'name' => 'Penumpang Test',
            'id_number' => '123456789',
            'phone' => '081234567890',
        ]);

        $payment = Payment::create([
            'booking_code' => 'BOOKTEST01',
            'user_id' => $user->id,
            'method' => 'transfer_bank',
            'amount' => 75000,
            'status' => 'pending',
            'reference' => 'PAYTEST01',
        ]);

        return compact(
            'user',
            'schedule',
            'seat',
            'booking',
            'payment'
        );
    }

    public function test_successful_payment_displays_e_ticket(): void
    {
        $data = $this->createPaidBooking();

        $data['payment']->markAsPaid();

        $this->actingAs($data['user'])
            ->get(route('payment.confirmation', 'BOOKTEST01'))
            ->assertOk()
            ->assertViewIs('tickets.show')
            ->assertViewHas('status', 'success')
            ->assertViewHas('code', 'BOOKTEST01')
            ->assertSee('Bus Test')
            ->assertSee('Madiun')
            ->assertSee('Surabaya')
            ->assertSee('A1');

        $this->assertDatabaseHas('tickets', [
            'booking_id' => $data['booking']->id,
        ]);
    }

    public function test_confirmation_redirects_if_payment_is_not_successful(): void
    {
        $data = $this->createPaidBooking();

        $this->actingAs($data['user'])
            ->get(route('payment.confirmation', 'BOOKTEST01'))
            ->assertRedirect(route('payment.show', 'BOOKTEST01'));
    }

    public function test_successful_payment_does_not_create_duplicate_tickets(): void
    {
        $data = $this->createPaidBooking();

        $data['payment']->markAsPaid();
        $data['payment']->markAsPaid();

        $this->assertDatabaseCount('tickets', 1);
    }

    public function test_user_cannot_access_another_users_e_ticket(): void
    {
        $data = $this->createPaidBooking();

        $data['payment']->markAsPaid();

        $anotherUser = User::factory()->create();

        $this->actingAs($anotherUser)
            ->get(route('payment.confirmation', 'BOOKTEST01'))
            ->assertNotFound();
    }

    public function test_e_ticket_contains_successful_payment_status(): void
    {
        $data = $this->createPaidBooking();

        $data['payment']->markAsPaid();

        $this->actingAs($data['user'])
            ->get(route('payment.confirmation', 'BOOKTEST01'))
            ->assertOk()
            ->assertSee('Berhasil');
    }
}
