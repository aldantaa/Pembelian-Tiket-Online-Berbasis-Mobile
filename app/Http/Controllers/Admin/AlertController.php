<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlertController extends Controller
{
    /**
     * Menampilkan peringatan dan notifikasi transaksi.
     */
    public function index(Request $request)
    {
        $query = DB::table('bookings')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->select(
                'bookings.id',
                'bookings.booking_code',
                'bookings.status',
                'bookings.price',
                'bookings.created_at',
                'bookings.expires_at',
                'users.name as user_name',
                'users.email as user_email',
                'schedules.kota_asal',
                'schedules.kota_tujuan',
                'schedules.waktu_keberangkatan'
            );

        // Filter kategori peringatan.
        if ($request->filled('type')) {
            $type = $request->input('type');

            if (in_array($type, ['pending', 'expired', 'paid'], true)) {
                $query->where('bookings.status', $type);
            }
        } else {
            // Secara default tampilkan transaksi yang perlu diperhatikan.
            $query->whereIn('bookings.status', [
                'pending',
                'expired'
            ]);
        }

        $alerts = $query
            ->orderByDesc('bookings.created_at')
            ->paginate(10)
            ->withQueryString();

        $pendingCount = DB::table('bookings')
            ->where('status', 'pending')
            ->count();

        $expiredCount = DB::table('bookings')
            ->where('status', 'expired')
            ->count();

        $paidCount = DB::table('bookings')
            ->where('status', 'paid')
            ->count();

        return view('admin.alerts', compact(
            'alerts',
            'pendingCount',
            'expiredCount',
            'paidCount'
        ));
    }
}
