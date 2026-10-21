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

        $tickets = $bookings
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

        return view('tickets.index', compact('tickets'));
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

    $first = $bookings->first();
    $schedule = $first->schedule;

    return view('tickets.show', [
        'bus' => [
            'id' => $schedule?->id,
            'name' => $schedule?->nama_bus ?? '-',
            'class' => 'Reguler',
            'depart' => $schedule?->waktu_keberangkatan?->format('H:i') ?? '-',
            'arrive' => $schedule?->waktu_tiba?->format('H:i') ?? '-',
            'price' => $schedule?->harga ?? 0,
        ],
        'query' => [
            'from' => $schedule?->kota_asal ?? '-',
            'to' => $schedule?->kota_tujuan ?? '-',
            'date' => $schedule?->waktu_keberangkatan?->format('d-m-Y') ?? '-',
        ],
        'seats' => $bookings
            ->map(fn ($booking) => $booking->seat?->seat_number)
            ->filter()
            ->values()
            ->all(),
        'code' => $booking_code,
        'status' => $bookings->contains(
            fn ($booking) => $booking->status === 'paid'
        ) ? 'success' : $first->status,
        'total' => $bookings->sum('price'),
    ]);
}

}
