<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserTransactionTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    private function createBookingForUser(User $user): void
    {
        $scheduleId = DB::table('schedules')->insertGetId([
            'nama_bus' => 'Bus Test',
            'kota_asal' => 'Madiun',
            'kota_tujuan' => 'Surabaya',
            'waktu_keberangkatan' => now()->addDay(),
            'waktu_tiba' => now()->addDay()->addHours(3),
            'harga' => 75000,
            'kapasitas' => 40,
            'kursi_tersedia' => 39,
            'status' => 'scheduled',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $seatId = DB::table('seats')->insertGetId([
            'schedule_id' => $scheduleId,
            'seat_number' => 'A1',
            'is_booked' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('bookings')->insert([
            'booking_code' => 'BOOKTEST01',
            'user_id' => $user->id,
            'schedule_id' => $scheduleId,
            'seat_id' => $seatId,
            'price' => 75000,
            'status' => 'pending',
            'expires_at' => now()->addMinutes(30),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_admin_can_view_users(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->get(route('admin.users'))
            ->assertOk()
            ->assertViewIs('admin.users');
    }

    public function test_admin_can_open_user_edit_page(): void
    {
        $admin = $this->createAdmin();

        $user = User::factory()->create([
            'name' => 'Pengguna Test',
            'role' => 'user',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.edit', $user->id))
            ->assertOk()
            ->assertViewIs('admin.users');
    }

    public function test_admin_can_update_user(): void
    {
        $admin = $this->createAdmin();

        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.users.update', $user->id), [
                'name' => 'Nama Baru',
                'email' => 'namabaru@example.com',
                'role' => 'admin',
                'password' => 'PasswordBaru123',
            ])
            ->assertRedirect(route('admin.users'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru',
            'email' => 'namabaru@example.com',
            'role' => 'admin',
        ]);

        $updatedUser = User::findOrFail($user->id);

        $this->assertTrue(
            Hash::check('PasswordBaru123', $updatedUser->password)
        );
    }

    public function test_admin_cannot_demote_own_account(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->put(route('admin.users.update', $admin->id), [
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => 'user',
            ])
            ->assertSessionHasErrors('role');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_delete_user_without_bookings(): void
    {
        $admin = $this->createAdmin();

        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.users.delete', $user->id))
            ->assertRedirect(route('admin.users'));

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->delete(route('admin.users.delete', $admin->id))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }

    public function test_admin_cannot_delete_another_admin(): void
    {
        $admin = $this->createAdmin();

        $anotherAdmin = $this->createAdmin();

        $this->actingAs($admin)
            ->delete(route('admin.users.delete', $anotherAdmin->id))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $anotherAdmin->id,
            'role' => 'admin',
        ]);
    }


    public function test_admin_cannot_delete_user_with_booking_history(): void
    {
        $admin = $this->createAdmin();

        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $this->createBookingForUser($user);

        $this->actingAs($admin)
            ->delete(route('admin.users.delete', $user->id))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);

        $this->assertDatabaseHas('bookings', [
            'booking_code' => 'BOOKTEST01',
            'user_id' => $user->id,
        ]);
    }


    public function test_admin_can_view_transactions(): void
    {
        $admin = $this->createAdmin();

        $user = User::factory()->create();

        DB::table('payments')->insert([
            'booking_code' => 'PAYMENTTEST01',
            'user_id' => $user->id,
            'method' => 'transfer_bank',
            'amount' => 75000,
            'status' => 'success',
            'reference' => 'REFTEST01',
            'paid_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.transactions'))
            ->assertOk()
            ->assertViewIs('admin.transactions');
    }

    public function test_regular_user_cannot_access_admin_users(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $this->actingAs($user)
            ->get(route('admin.users'))
            ->assertForbidden();
    }

    public function test_guest_is_redirected_to_login_from_admin_users(): void
    {
        $this->get(route('admin.users'))
            ->assertRedirect(route('login'));
    }
}
