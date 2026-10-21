<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderHistoryController extends Controller
{
    /**
     * Menampilkan riwayat pemesanan tiket.
     */
    public function index(Request $request)
    {
        $query = DB::table('bookings')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->leftJoin('payments', 'bookings.booking_code', '=', 'payments.booking_code')
            ->select(
                'bookings.id',
                'bookings.booking_code',
                'bookings.user_id',
                'bookings.schedule_id',
                'bookings.seat_id',
                'bookings.price',
                'bookings.status',
                'bookings.created_at',
                'users.name as user_name',
                'users.email as user_email',
                'schedules.kota_asal',
                'schedules.kota_tujuan',
                'schedules.waktu_keberangkatan',
                'payments.method as payment_method',
                'payments.status as payment_status',
                'payments.paid_at'
            );

        // Pencarian kode booking atau nama pengguna.
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('bookings.booking_code', 'like', "%{$search}%")
                    ->orWhere('users.name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan status booking.
        if ($request->filled('status')) {
            $query->where('bookings.status', $request->input('status'));
        }

        $orders = $query
            ->orderByDesc('bookings.created_at')
            ->paginate(10)
            ->withQueryString();

        $totalOrders = DB::table('bookings')->count();

        $paidOrders = DB::table('bookings')
            ->where('status', 'paid')
            ->count();

        $pendingOrders = DB::table('bookings')
            ->where('status', 'pending')
            ->count();

        $expiredOrders = DB::table('bookings')
            ->where('status', 'expired')
            ->count();

        return view('admin.orders', compact(
            'orders',
            'totalOrders',
            'paidOrders',
            'pendingOrders',
            'expiredOrders'
        ));
    }
}
