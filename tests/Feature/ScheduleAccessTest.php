<?php

namespace Tests\Feature;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduleAccessTest extends TestCase
{
    use RefreshDatabase;

    private function createSchedule(): Schedule
    {
        return Schedule::create([
            'nama_bus' => 'Bus Test',
            'kota_asal' => 'Madiun',
            'kota_tujuan' => 'Surabaya',
            'waktu_keberangkatan' => '2026-11-20 08:00:00',
            'waktu_tiba' => '2026-11-20 11:00:00',
            'harga' => 75000,
            'kapasitas' => 40,
            'kursi_tersedia' => 40,
            'fasilitas' => 'AC',
        ]);
    }

    public function test_admin_cannot_access_customer_schedule_list(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('schedules'))
            ->assertForbidden();
    }

    public function test_admin_cannot_access_customer_seat_selection(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $schedule = $this->createSchedule();

        $this->actingAs($admin)
            ->get(route('booking.seats', [
                'schedule_id' => $schedule->id,
            ]))
            ->assertForbidden();
    }

    public function test_regular_user_can_access_customer_schedule_list(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('schedules'))
            ->assertOk();
    }

    public function test_guest_can_access_customer_schedule_list(): void
    {
        $this->get(route('schedules'))
            ->assertOk();
    }

    public function test_admin_can_still_manage_schedules(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.schedules'))
            ->assertOk();
    }
}
