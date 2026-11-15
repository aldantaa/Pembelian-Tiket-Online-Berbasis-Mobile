<?php

namespace Tests\Feature;

use App\Models\Alert;
use App\Models\Bus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ScheduleNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function loginAdmin(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);
    }

    private function createSchedule(): array
    {
        $bus = Bus::create([
            'nama_bus' => 'Harapan Jaya',
            'nomor_polisi' => 'AE1234AB',
            'kapasitas' => 40,
            'status' => 'aktif',
        ]);

        $scheduleId = DB::table('schedules')->insertGetId([
            'bus_id' => $bus->id,
            'nama_bus' => $bus->nama_bus,
            'kota_asal' => 'Madiun',
            'kota_tujuan' => 'Surabaya',
            'waktu_keberangkatan' => '2026-12-01 08:00:00',
            'waktu_tiba' => '2026-12-01 11:00:00',
            'harga' => 75000,
            'kapasitas' => 40,
            'kursi_tersedia' => 40,
            'fasilitas' => 'AC',
            'status' => 'scheduled',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [$bus, $scheduleId];
    }

    private function createBooking(
        int $userId,
        int $scheduleId,
        string $seatNumber,
        string $status = 'paid'
    ): void {
        $seatId = DB::table('seats')->insertGetId([
            'schedule_id' => $scheduleId,
            'seat_number' => $seatNumber,
            'is_booked' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('bookings')->insert([
            'user_id' => $userId,
            'schedule_id' => $scheduleId,
            'seat_id' => $seatId,
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function updateSchedule(int $scheduleId, string $busName = 'Harapan Jaya')
    {
        return $this->put(route('admin.schedules.update', $scheduleId), [
            'bus' => $busName,
            'from' => 'Madiun',
            'to' => 'Surabaya',
            'depart' => '2026-12-01 09:00:00',
            'arrive' => '2026-12-01 12:00:00',
            'price' => 80000,
        ]);
    }

    public function test_schedule_change_notifies_each_booking_user_only_once(): void
    {
        $this->loginAdmin();

        [$bus, $scheduleId] = $this->createSchedule();

        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        // Satu pengguna memiliki dua pemesanan.
        $this->createBooking($user->id, $scheduleId, 'A1');
        $this->createBooking($user->id, $scheduleId, 'A2');

        // Pengguna lain juga memiliki pemesanan.
        $this->createBooking($otherUser->id, $scheduleId, 'A3');

        $response = $this->updateSchedule($scheduleId);

        $response->assertRedirect(route('admin.schedules'));

        // Setiap pengguna hanya menerima satu notifikasi.
        $this->assertSame(
            1,
            Alert::where('user_id', $user->id)
                ->where('type', 'schedule_change')
                ->count()
        );

        $this->assertSame(
            1,
            Alert::where('user_id', $otherUser->id)
                ->where('type', 'schedule_change')
                ->count()
        );

        $this->assertDatabaseHas('alerts', [
            'user_id' => $user->id,
            'schedule_id' => $scheduleId,
            'type' => 'schedule_change',
        ]);
    }

    public function test_schedule_change_does_not_notify_users_without_bookings(): void
    {
        $this->loginAdmin();

        [$bus, $scheduleId] = $this->createSchedule();

        User::factory()->create();

        $response = $this->updateSchedule($scheduleId);

        $response->assertRedirect(route('admin.schedules'));

        $this->assertDatabaseCount('alerts', 0);
    }

    public function test_schedule_change_does_not_notify_cancelled_or_expired_bookings(): void
    {
        $this->loginAdmin();

        [$bus, $scheduleId] = $this->createSchedule();

        $cancelledUser = User::factory()->create();
        $expiredUser = User::factory()->create();

        $this->createBooking(
            $cancelledUser->id,
            $scheduleId,
            'A1',
            'cancelled'
        );

        $this->createBooking(
            $expiredUser->id,
            $scheduleId,
            'A2',
            'expired'
        );

        $response = $this->updateSchedule($scheduleId);

        $response->assertRedirect(route('admin.schedules'));

        $this->assertDatabaseCount('alerts', 0);
    }
}
