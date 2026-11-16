<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BusTest extends TestCase
{
    use RefreshDatabase;

    private function loginAdmin(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);
    }

    public function test_admin_can_view_bus_list(): void
    {
        $this->loginAdmin();

        Bus::create([
            'nama_bus' => 'Harapan Jaya',
            'nomor_polisi' => 'AE1234AB',
            'kapasitas' => 40,
            'status' => 'aktif',
        ]);

        $response = $this->getJson('/admin/buses');

        $response->assertOk()
            ->assertJsonPath('data.0.nama_bus', 'Harapan Jaya')
            ->assertJsonPath('data.0.kapasitas', 40);
    }

    public function test_admin_can_add_bus_with_capacity_40(): void
    {
        $this->loginAdmin();

        $response = $this->postJson('/admin/buses', [
            'nama_bus' => 'Harapan Jaya',
            'nomor_polisi' => 'AE1234AB',
            'kapasitas' => 40,
            'status' => 'aktif',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('buses', [
            'nama_bus' => 'Harapan Jaya',
            'nomor_polisi' => 'AE1234AB',
            'kapasitas' => 40,
            'status' => 'aktif',
        ]);
    }

    public function test_admin_cannot_add_bus_with_capacity_other_than_40(): void
    {
        $this->loginAdmin();

        $response = $this->postJson('/admin/buses', [
            'nama_bus' => 'Bus Kapasitas 45',
            'nomor_polisi' => 'AE1234CD',
            'kapasitas' => 45,
            'status' => 'aktif',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['kapasitas']);

        $this->assertDatabaseMissing('buses', [
            'nama_bus' => 'Bus Kapasitas 45',
        ]);
    }

    public function test_admin_can_update_bus(): void
    {
        $this->loginAdmin();

        $bus = Bus::create([
            'nama_bus' => 'Bus Lama',
            'nomor_polisi' => 'AE1111AA',
            'kapasitas' => 40,
            'status' => 'aktif',
        ]);

        $response = $this->putJson("/admin/buses/{$bus->id}", [
            'nama_bus' => 'Bus Baru',
            'nomor_polisi' => 'AE2222BB',
            'kapasitas' => 40,
            'status' => 'nonaktif',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('buses', [
            'id' => $bus->id,
            'nama_bus' => 'Bus Baru',
            'nomor_polisi' => 'AE2222BB',
            'kapasitas' => 40,
            'status' => 'nonaktif',
        ]);
    }

    public function test_admin_cannot_update_bus_to_capacity_other_than_40(): void
    {
        $this->loginAdmin();

        $bus = Bus::create([
            'nama_bus' => 'Harapan Jaya',
            'nomor_polisi' => 'AE1234AB',
            'kapasitas' => 40,
            'status' => 'aktif',
        ]);

        $response = $this->putJson("/admin/buses/{$bus->id}", [
            'nama_bus' => 'Harapan Jaya',
            'nomor_polisi' => 'AE1234AB',
            'kapasitas' => 45,
            'status' => 'aktif',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['kapasitas']);

        $this->assertDatabaseHas('buses', [
            'id' => $bus->id,
            'kapasitas' => 40,
        ]);
    }

    public function test_admin_can_delete_bus_that_is_not_used_by_schedule(): void
    {
        $this->loginAdmin();

        $bus = Bus::create([
            'nama_bus' => 'Bus Tidak Terpakai',
            'nomor_polisi' => 'AE3333CC',
            'kapasitas' => 40,
            'status' => 'aktif',
        ]);

        $response = $this->deleteJson("/admin/buses/{$bus->id}");

        $response->assertOk();

        $this->assertDatabaseMissing('buses', [
            'id' => $bus->id,
        ]);
    }

    public function test_admin_cannot_delete_bus_that_is_used_by_schedule(): void
    {
        $this->loginAdmin();

        $bus = Bus::create([
            'nama_bus' => 'Harapan Jaya',
            'nomor_polisi' => 'AE1234AB',
            'kapasitas' => 40,
            'status' => 'aktif',
        ]);

        DB::table('schedules')->insert([
            'bus_id' => $bus->id,
            'nama_bus' => $bus->nama_bus,
            'kota_asal' => 'Madiun',
            'kota_tujuan' => 'Surabaya',
            'waktu_keberangkatan' => '2026-12-01 08:00:00',
            'waktu_tiba' => '2026-12-01 12:00:00',
            'harga' => 75000,
            'kapasitas' => 40,
            'kursi_tersedia' => 40,
            'fasilitas' => 'AC',
            'status' => 'scheduled',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->deleteJson("/admin/buses/{$bus->id}");

        $response->assertStatus(409);

        $this->assertDatabaseHas('buses', [
            'id' => $bus->id,
        ]);
    }
}
