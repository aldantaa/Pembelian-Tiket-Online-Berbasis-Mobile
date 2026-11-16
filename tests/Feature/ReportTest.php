<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    private function createSchedule(): int
    {
        return DB::table('schedules')->insertGetId([
            'nama_bus' => 'Bus Laporan',
            'kota_asal' => 'Madiun',
            'kota_tujuan' => 'Surabaya',
            'waktu_keberangkatan' => now()->addDay(),
            'waktu_tiba' => now()->addDay()->addHours(3),
            'harga' => 75000,
            'kapasitas' => 40,
            'kursi_tersedia' => 38,
            'status' => 'scheduled',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createPaidTransaction(
        User $user,
        string $bookingCode = 'BOOKREPORT01',
        int $numberOfSeats = 1,
        int $amount = 75000,
        ?int $scheduleId = null,
        ?string $paidAt = null
    ): void {
        $scheduleId ??= $this->createSchedule();

        for ($i = 1; $i <= $numberOfSeats; $i++) {
            $seatId = DB::table('seats')->insertGetId([
                'schedule_id' => $scheduleId,
                'seat_number' => 'A' . $i,
                'is_booked' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('bookings')->insert([
                'booking_code' => $bookingCode,
                'user_id' => $user->id,
                'schedule_id' => $scheduleId,
                'seat_id' => $seatId,
                'price' => $amount / $numberOfSeats,
                'status' => 'paid',
                'expires_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('payments')->insert([
            'booking_code' => $bookingCode,
            'user_id' => $user->id,
            'method' => 'transfer_bank',
            'amount' => $amount,
            'status' => 'success',
            'reference' => 'REF-' . $bookingCode,
            'paid_at' => $paidAt ?? now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_admin_can_view_report_dashboard(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewIs('admin.dashboard')
            ->assertViewHas('stats')
            ->assertViewHas('chart')
            ->assertViewHas('routes');
    }

    public function test_dashboard_calculates_total_users_bookings_and_revenue(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();

        $this->createPaidTransaction(
            $user,
            'BOOKREPORT02',
            1,
            75000
        );

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewHas('stats', function ($stats) {
                return $stats['Total pengguna'] === 2
                    && $stats['Total pemesanan'] === 1
                    && $stats['Total pendapatan'] === 'Rp 75.000';
            });
    }

    public function test_dashboard_excludes_pending_payments_from_revenue(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();

        $this->createPaidTransaction(
            $user,
            'BOOKREPORT03',
            1,
            75000
        );

        DB::table('payments')->insert([
            'booking_code' => 'BOOKPENDING01',
            'user_id' => $user->id,
            'method' => 'transfer_bank',
            'amount' => 50000,
            'status' => 'pending',
            'reference' => 'REF-PENDING01',
            'paid_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewHas('stats', function ($stats) {
                return $stats['Total pendapatan'] === 'Rp 75.000';
            });
    }

    public function test_dashboard_displays_daily_revenue_chart(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();

        $this->createPaidTransaction(
            $user,
            'BOOKDAILY01',
            1,
            75000,
            null,
            now()->toDateTimeString()
        );

        $this->actingAs($admin)
            ->get(route('admin.dashboard', ['period' => 'harian']))
            ->assertOk()
            ->assertViewHas('chart', function ($chart) {
            return in_array(75000.0, $chart, true);
        });
    }

    public function test_dashboard_displays_monthly_revenue_chart(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();

        $this->createPaidTransaction(
            $user,
            'BOOKMONTH01',
            1,
            125000,
            null,
            now()->toDateTimeString()
        );

        $this->actingAs($admin)
            ->get(route('admin.dashboard', ['period' => 'bulanan']))
            ->assertOk()
            ->assertViewHas('chart', function ($chart) {
            return in_array(125000.0, $chart, true);
        });
    }

    public function test_dashboard_does_not_duplicate_revenue_for_multiple_seats(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();

        $this->createPaidTransaction(
            $user,
            'BOOKMULTI01',
            2,
            150000
        );

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewHas('routes', function ($routes) {
                return count($routes) === 1
                    && $routes[0]['passengers'] === 2
                    && $routes[0]['revenue'] === 150000.0;
            });
    }

    public function test_admin_can_export_payment_report_as_csv(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();

        $this->createPaidTransaction(
            $user,
            'BOOKEXPORT01',
            1,
            75000
        );

        $response = $this->actingAs($admin)
            ->get(route('admin.export'));

        $response->assertOk();

        $this->assertStringContainsString(
            'text/csv',
            $response->headers->get('Content-Type')
        );

        $csv = $response->streamedContent();

        $this->assertStringContainsString('Kode Booking', $csv);
        $this->assertStringContainsString('BOOKEXPORT01', $csv);
        $this->assertStringContainsString('Madiun', $csv);
        $this->assertStringContainsString('Surabaya', $csv);
        $this->assertStringContainsString('75000', $csv);
    }

    public function test_regular_user_cannot_access_report_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_guest_cannot_access_report_dashboard(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_export_report(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $this->actingAs($user)
            ->get(route('admin.export'))
            ->assertForbidden();
    }

    public function test_guest_cannot_export_report(): void
    {
        $this->get(route('admin.export'))
            ->assertRedirect(route('login'));
    }
}
