<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrderController extends Controller
{
    /**
     * Menampilkan riwayat pesanan pengguna yang login.
     */
    public function index(Request $request)
    {
        Booking::releaseExpired();

        $bookings = Booking::query()
            ->where('user_id', $request->user()->id)
            ->with(['schedule', 'seat', 'ticket'])
            ->orderByDesc('created_at')
            ->get();

        $orders = $bookings
            ->groupBy('booking_code')
            ->map(function ($items) {
                $first = $items->first();
                $schedule = $first->schedule;

                $departureTime = $schedule?->waktu_keberangkatan;

                $isActive = false;
                $journeyStatus = 'Belum diketahui';

                if ($schedule) {
                    $scheduleStatus = $schedule->status ?? null;

                    if ($scheduleStatus === 'cancelled') {
                        $journeyStatus = 'Dibatalkan';
                    } elseif ($scheduleStatus === 'delayed') {
                        $journeyStatus = 'Ditunda';
                    } elseif ($scheduleStatus === 'departed') {
                        $journeyStatus = 'Sudah berangkat';
                    } elseif ($scheduleStatus === 'arrived') {
                        $journeyStatus = 'Sudah tiba';
                    } elseif ($scheduleStatus === 'scheduled') {
                        $journeyStatus = 'Terjadwal';
                    } elseif ($departureTime) {
                        $journeyStatus = Carbon::parse($departureTime)->isFuture()
                            ? 'Belum berangkat'
                            : 'Waktu keberangkatan telah tiba';
                    }

                    $isActive = $items->contains(
                        fn ($booking) =>
                            $booking->status === 'paid'
                            && $booking->ticket !== null
                    )
                    && $departureTime
                    && Carbon::parse($departureTime)->isFuture()
                    && ! in_array(
                        $scheduleStatus,
                        ['cancelled', 'departed', 'arrived'],
                        true
                    );
                }

                return [
                    'booking_code' => $first->booking_code,
                    'bookings' => $items,
                    'schedule' => $schedule,
                    'seats' => $items
                        ->map(fn ($booking) => $booking->seat?->seat_number)
                        ->filter()
                        ->values(),
                    'total' => $items->sum('price'),
                    'status' => $items->contains(
                        fn ($booking) => $booking->status === 'paid'
                    ) ? 'paid' : $first->status,
                    'journey_status' => $journeyStatus,
                    'is_active' => (bool) $isActive,
                    'created_at' => $first->created_at,
                ];
            })
            ->values();

        return view('orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail satu pesanan.
     */
    public function show(Request $request, string $booking_code)
    {
        Booking::releaseExpired();

        $bookings = Booking::query()
            ->where('user_id', $request->user()->id)
            ->where('booking_code', $booking_code)
            ->with(['schedule', 'seat', 'passenger', 'ticket'])
            ->orderBy('id')
            ->get();

        abort_if($bookings->isEmpty(), 404);

        $schedule = $bookings->first()->schedule;

        return view('orders.show', [
            'bookingCode' => $booking_code,
            'bookings' => $bookings,
            'schedule' => $schedule,
            'total' => $bookings->sum('price'),
        ]);
    }
}
