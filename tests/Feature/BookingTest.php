<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\Passenger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private function createSchedule(): Schedule
    {
        return Schedule::create([
            'nama_bus' => 'Bus Test',
            'kota_asal' => 'Madiun',
            'kota_tujuan' => 'Surabaya',
            'waktu_keberangkatan' => '2026-12-01 08:00:00',
            'waktu_tiba' => '2026-12-01 11:00:00',
            'harga' => 75000,
            'kapasitas' => 40,
            'kursi_tersedia' => 40,
            'fasilitas' => 'AC',
        ]);
    }

    private function createSeat(Schedule $schedule, string $number): Seat
    {
        return Seat::create([
            'schedule_id' => $schedule->id,
            'seat_number' => $number,
            'is_booked' => false,
        ]);
    }

    private function passengerData(array $seatNumbers): array
    {
        $passengers = [];

        foreach ($seatNumbers as $number) {
            $passengers[$number] = [
                'name' => 'Penumpang ' . $number,
                'id_number' => '123456789',
                'phone' => '08123456789',
            ];
        }

        return $passengers;
    }

    public function test_user_can_book_one_seat(): void
    {
        $user = User::factory()->create();
        $schedule = $this->createSchedule();
        $seat = $this->createSeat($schedule, 'A1');

        $response = $this->actingAs($user)->post(route('bookings.store'), [
            'schedule_id' => $schedule->id,
            'seats' => 'A1',
            'passengers' => $this->passengerData(['A1']),
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'seat_id' => $seat->id,
            'price' => 75000,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('passengers', [
            'name' => 'Penumpang A1',
        ]);

        $this->assertDatabaseHas('seats', [
            'id' => $seat->id,
            'is_booked' => true,
        ]);
    }

    public function test_user_can_book_multiple_seats(): void
    {
        $user = User::factory()->create();
        $schedule = $this->createSchedule();

        $this->createSeat($schedule, 'A1');
        $this->createSeat($schedule, 'A2');

        $response = $this->actingAs($user)->post(route('bookings.store'), [
            'schedule_id' => $schedule->id,
            'seats' => 'A1,A2',
            'passengers' => $this->passengerData(['A1', 'A2']),
        ]);

        $response->assertRedirect();

        $this->assertDatabaseCount('bookings', 2);
        $this->assertDatabaseCount('passengers', 2);

        $codes = Booking::pluck('booking_code')->unique();

        $this->assertCount(1, $codes);
    }

    public function test_booking_generates_booking_code(): void
    {
        $user = User::factory()->create();
        $schedule = $this->createSchedule();

        $this->createSeat($schedule, 'A1');

        $this->actingAs($user)->post(route('bookings.store'), [
            'schedule_id' => $schedule->id,
            'seats' => 'A1',
            'passengers' => $this->passengerData(['A1']),
        ]);

        $booking = Booking::first();

        $this->assertNotNull($booking);
        $this->assertNotEmpty($booking->booking_code);
        $this->assertStringStartsWith('BK', $booking->booking_code);
    }


public function test_booking_has_expiration_time_30_minutes(): void
{
    $user = User::factory()->create();
    $schedule = $this->createSchedule();

    $this->createSeat($schedule, 'A1');

    $fixedTime = now()->startOfSecond();

    $this->travelTo($fixedTime);

    $this->actingAs($user)->post(route('bookings.store'), [
        'schedule_id' => $schedule->id,
        'seats' => 'A1',
        'passengers' => $this->passengerData(['A1']),
    ]);

    $booking = Booking::first();

    $this->assertNotNull($booking);
    $this->assertNotNull($booking->expires_at);

    $this->assertEquals(
        $fixedTime->copy()->addMinutes(30)->timestamp,
        $booking->expires_at->timestamp
    );

    $this->travelBack();
}


    public function test_booked_seat_cannot_be_booked_again(): void
    {
        $user = User::factory()->create();
        $schedule = $this->createSchedule();

        $seat = $this->createSeat($schedule, 'A1');

        $seat->update(['is_booked' => true]);

        $response = $this->actingAs($user)->from('/schedules')
            ->post(route('bookings.store'), [
                'schedule_id' => $schedule->id,
                'seats' => 'A1',
                'passengers' => $this->passengerData(['A1']),
            ]);

        $response->assertSessionHasErrors('seats');

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_user_cannot_book_more_than_five_seats(): void
    {
        $user = User::factory()->create();
        $schedule = $this->createSchedule();

        foreach (['A1', 'A2', 'A3', 'A4', 'A5', 'A6'] as $number) {
            $this->createSeat($schedule, $number);
        }

        $response = $this->actingAs($user)->post(route('bookings.store'), [
            'schedule_id' => $schedule->id,
            'seats' => 'A1,A2,A3,A4,A5,A6',
            'passengers' => $this->passengerData([
                'A1', 'A2', 'A3', 'A4', 'A5', 'A6'
            ]),
        ]);

        $response->assertSessionHasErrors('seats');

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_booking_requires_passenger_data_for_each_seat(): void
    {
        $user = User::factory()->create();
        $schedule = $this->createSchedule();

        $this->createSeat($schedule, 'A1');
        $this->createSeat($schedule, 'A2');

        $response = $this->actingAs($user)->post(route('bookings.store'), [
            'schedule_id' => $schedule->id,
            'seats' => 'A1,A2',
            'passengers' => [
                'A1' => [
                    'name' => 'Penumpang A1',
                ],
            ],
        ]);

        $response->assertSessionHasErrors('passengers');

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_guest_cannot_create_booking(): void
    {
        $schedule = $this->createSchedule();
        $this->createSeat($schedule, 'A1');

        $response = $this->post(route('bookings.store'), [
            'schedule_id' => $schedule->id,
            'seats' => 'A1',
            'passengers' => $this->passengerData(['A1']),
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_booking_rolls_back_if_one_seat_is_unavailable(): void
    {
        $user = User::factory()->create();
        $schedule = $this->createSchedule();

        $this->createSeat($schedule, 'A1');
        $this->createSeat($schedule, 'A2')->update([
            'is_booked' => true,
        ]);

        $response = $this->actingAs($user)->post(route('bookings.store'), [
            'schedule_id' => $schedule->id,
            'seats' => 'A1,A2',
            'passengers' => $this->passengerData(['A1', 'A2']),
        ]);

        $response->assertSessionHasErrors('seats');

        $this->assertDatabaseCount('bookings', 0);
        $this->assertDatabaseCount('passengers', 0);

        $this->assertDatabaseHas('seats', [
            'seat_number' => 'A1',
            'is_booked' => false,
        ]);
    }
}
