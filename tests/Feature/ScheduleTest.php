<?php

namespace Tests\Feature;

use App\Models\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    private function createSchedule(array $data = []): Schedule
    {
        return Schedule::create(array_merge([
            'nama_bus' => 'Bus Test',
            'kota_asal' => 'Madiun',
            'kota_tujuan' => 'Surabaya',
            'waktu_keberangkatan' => '2026-11-10 08:00:00',
            'waktu_tiba' => '2026-11-10 11:00:00',
            'harga' => 75000,
            'kapasitas' => 40,
            'kursi_tersedia' => 40,
            'fasilitas' => 'AC',
        ], $data));
    }

    // 1. Pengguna dapat melihat daftar jadwal bus.
    public function test_user_can_view_schedule_list(): void
    {
        $this->createSchedule([
            'nama_bus' => 'Bus Madiun Surabaya',
        ]);

        $response = $this->get(route('schedules'));

        $response->assertStatus(200);
        $response->assertSee('Bus Madiun Surabaya');
    }

    // 2. Pengguna dapat mencari jadwal berdasarkan kota asal.
    public function test_user_can_filter_schedules_by_origin(): void
    {
        $this->createSchedule([
            'nama_bus' => 'Bus dari Madiun',
            'kota_asal' => 'Madiun',
        ]);

        $this->createSchedule([
            'nama_bus' => 'Bus dari Kediri',
            'kota_asal' => 'Kediri',
        ]);

        $response = $this->get(route('schedules', [
            'from' => 'Madiun',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Bus dari Madiun');
        $response->assertDontSee('Bus dari Kediri');
    }

    // 3. Pengguna dapat mencari jadwal berdasarkan kota tujuan.
    public function test_user_can_filter_schedules_by_destination(): void
    {
        $this->createSchedule([
            'nama_bus' => 'Bus tujuan Surabaya',
            'kota_tujuan' => 'Surabaya',
        ]);

        $this->createSchedule([
            'nama_bus' => 'Bus tujuan Malang',
            'kota_tujuan' => 'Malang',
        ]);

        $response = $this->get(route('schedules', [
            'to' => 'Surabaya',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Bus tujuan Surabaya');
        $response->assertDontSee('Bus tujuan Malang');
    }

    // 4. Pengguna dapat mencari jadwal berdasarkan tanggal.
    public function test_user_can_filter_schedules_by_departure_date(): void
    {
        $this->createSchedule([
            'nama_bus' => 'Bus tanggal 10',
            'waktu_keberangkatan' => '2026-11-10 08:00:00',
        ]);

        $this->createSchedule([
            'nama_bus' => 'Bus tanggal 11',
            'waktu_keberangkatan' => '2026-11-11 08:00:00',
        ]);

        $response = $this->get(route('schedules', [
            'date' => '2026-11-10',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Bus tanggal 10');
        $response->assertDontSee('Bus tanggal 11');
    }

    // 5. Pengguna dapat menggabungkan filter asal, tujuan, dan tanggal.
    public function test_user_can_combine_schedule_search_filters(): void
    {
        $this->createSchedule([
            'nama_bus' => 'Jadwal yang sesuai',
            'kota_asal' => 'Madiun',
            'kota_tujuan' => 'Surabaya',
            'waktu_keberangkatan' => '2026-11-10 08:00:00',
        ]);

        $this->createSchedule([
            'nama_bus' => 'Jadwal kota berbeda',
            'kota_asal' => 'Kediri',
            'kota_tujuan' => 'Surabaya',
            'waktu_keberangkatan' => '2026-11-10 09:00:00',
        ]);

        $this->createSchedule([
            'nama_bus' => 'Jadwal tanggal berbeda',
            'kota_asal' => 'Madiun',
            'kota_tujuan' => 'Surabaya',
            'waktu_keberangkatan' => '2026-11-11 08:00:00',
        ]);

        $response = $this->get(route('schedules', [
            'from' => 'Madiun',
            'to' => 'Surabaya',
            'date' => '2026-11-10',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Jadwal yang sesuai');
        $response->assertDontSee('Jadwal kota berbeda');
        $response->assertDontSee('Jadwal tanggal berbeda');
    }

    // 6. Pencarian tidak membedakan huruf besar dan kecil.
    public function test_schedule_search_is_case_insensitive(): void
    {
        $this->createSchedule([
            'nama_bus' => 'Bus Madiun',
            'kota_asal' => 'Madiun',
        ]);

        $response = $this->get(route('schedules', [
            'from' => 'mAdIuN',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Bus Madiun');
    }

    // 7. Pencarian menampilkan hasil kosong jika jadwal tidak ditemukan.
    public function test_search_returns_empty_result_when_no_schedule_matches(): void
    {
        $this->createSchedule([
            'nama_bus' => 'Bus Madiun',
        ]);

        $response = $this->get(route('schedules', [
            'from' => 'Jakarta',
        ]));

        $response->assertStatus(200);
        $response->assertDontSee('Bus Madiun');

        $response->assertViewHas('schedules', function ($schedules) {
            return $schedules->isEmpty();
        });
    }
}
