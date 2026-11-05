<?php

namespace Tests\Feature;

use App\Models\Schedule;
use App\Models\Seat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SeatTest extends TestCase
{
    use RefreshDatabase;

    private function createSchedule(): Schedule
    {
        return Schedule::create([
            'nama_bus' => 'Bus Test',
            'kota_asal' => 'Madiun',
            'kota_tujuan' => 'Surabaya',
            'waktu_keberangkatan' => '2026-11-10 08:00:00',
            'waktu_tiba' => '2026-11-10 11:00:00',
            'harga' => 75000,
            'kapasitas' => 40,
            'kursi_tersedia' => 40,
            'fasilitas' => 'AC',
        ]);
    }

    private function createSeat(
        Schedule $schedule,
        string $number,
        bool $booked = false
    ): Seat {
        return Seat::create([
            'schedule_id' => $schedule->id,
            'seat_number' => $number,
            'is_booked' => $booked,
        ]);
    }

    // 1. Pengguna dapat membuka halaman pemilihan kursi.
    public function test_user_can_open_seat_selection_page(): void
    {
        $schedule = $this->createSchedule();

        $this->createSeat($schedule, 'A1');
        $this->createSeat($schedule, 'A2');

        $response = $this->get(route('booking.seats', [
            'schedule_id' => $schedule->id,
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('schedule');
        $response->assertViewHas('seats');
    }

    // 2. Kursi tersedia ditampilkan.
    public function test_available_seats_are_displayed(): void
    {
        $schedule = $this->createSchedule();

        $this->createSeat($schedule, 'A1', false);
        $this->createSeat($schedule, 'A2', false);

        $response = $this->get(route('booking.seats', [
            'schedule_id' => $schedule->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('A1');
        $response->assertSee('A2');

        $response->assertViewHas('availableSeats', 2);
    }

    // 3. Kursi yang sudah dipesan tetap ditampilkan sebagai terpesan.
    public function test_booked_seats_are_displayed(): void
    {
        $schedule = $this->createSchedule();

        $this->createSeat($schedule, 'A1', true);
        $this->createSeat($schedule, 'A2', false);

        $response = $this->get(route('booking.seats', [
            'schedule_id' => $schedule->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('A1');
        $response->assertSee('A2');

        $response->assertViewHas('availableSeats', 1);
    }

    // 4. Jumlah seluruh kursi dihitung dengan benar.
    public function test_total_seats_are_counted_correctly(): void
    {
        $schedule = $this->createSchedule();

        $this->createSeat($schedule, 'A1');
        $this->createSeat($schedule, 'A2');
        $this->createSeat($schedule, 'B1', true);

        $response = $this->get(route('booking.seats', [
            'schedule_id' => $schedule->id,
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('totalSeats', 3);
        $response->assertViewHas('availableSeats', 2);
    }

    // 5. Kursi diurutkan berdasarkan nomor.
    public function test_seats_are_ordered_by_seat_number(): void
    {
        $schedule = $this->createSchedule();

        $this->createSeat($schedule, 'B2');
        $this->createSeat($schedule, 'A3');
        $this->createSeat($schedule, 'A1');
        $this->createSeat($schedule, 'B1');

        $response = $this->get(route('booking.seats', [
            'schedule_id' => $schedule->id,
        ]));

        $response->assertStatus(200);

        $response->assertViewHas('seats', function ($seats) {
            return $seats->pluck('seat_number')->all()
                === ['A1', 'A3', 'B1', 'B2'];
        });
    }

    // 6. Booking kedaluwarsa mengembalikan kursi menjadi tersedia.
    public function test_expired_booking_releases_its_seat(): void
    {
        $schedule = $this->createSchedule();

        $user = User::factory()->create();

        $seat = $this->createSeat($schedule, 'A1', true);

        $bookingId = DB::table('bookings')->insertGetId([
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'seat_id' => $seat->id,
            'status' => 'pending',
            'expires_at' => now()->subHour(),
            'created_at' => now()->subHours(2),
            'updated_at' => now()->subHours(2),
        ]);

        $response = $this->get(route('booking.seats', [
            'schedule_id' => $schedule->id,
        ]));

        $response->assertStatus(200);

        $this->assertDatabaseHas('bookings', [
            'id' => $bookingId,
            'status' => 'expired',
        ]);

        $this->assertDatabaseHas('seats', [
            'id' => $seat->id,
            'is_booked' => false,
        ]);
    }

    // 7. Jadwal yang tidak ditemukan menghasilkan 404.
    public function test_seat_page_returns_404_for_missing_schedule(): void
    {
        $response = $this->get(route('booking.seats', [
            'schedule_id' => 99999,
        ]));

        $response->assertNotFound();
    }
}
