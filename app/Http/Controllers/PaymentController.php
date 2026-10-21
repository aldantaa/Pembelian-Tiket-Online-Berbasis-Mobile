<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public const METHODS = [
        'transfer_bank'   => 'Transfer Bank',
        'virtual_account' => 'Virtual Account',
        'e_wallet'        => 'E-Wallet',
        'qris'            => 'QRIS',
    ];

    // KF-06: halaman pembayaran
    public function show(string $booking_code)
    {
        Booking::releaseExpired();

        $bookings = $this->ownBookings($booking_code);
        $first = $bookings->first();

        if ($first->status === 'paid') {
            return redirect()->route('payment.confirmation', $booking_code);
        }

        return view('booking.payment', [
            'booking_code' => $booking_code,
            'bookings'     => $bookings,
            'schedule'     => $first->schedule,
            'total'        => $bookings->sum('price'),
            'methods'      => collect(self::METHODS)
        ->map(fn ($label, $key) => [$key, $label])
        ->values()
        ->all(),
        ]);
    }

    // KF-06: proses pembayaran -> catatan pembayaran berstatus pending
    public function pay(Request $request, string $booking_code)
    {
        Booking::releaseExpired();

        $bookings = $this->ownBookings($booking_code);

        if ($bookings->first()->status !== 'pending') {
            return redirect()->route('payment.show', $booking_code)
                ->with('error', 'Pesanan ini tidak bisa dibayar (sudah dibayar atau kedaluwarsa).');
        }

        $data = $request->validate([
            'method' => ['required', Rule::in(array_keys(self::METHODS))],
        ]);

        $payment = Payment::updateOrCreate(
            ['booking_code' => $booking_code],
            [
                'user_id' => $request->user()->id,
                'method'  => $data['method'],
                'amount'  => $bookings->sum('price'),
                'status'  => 'pending',
            ]
        );

        if (! $payment->reference) {
            $payment->update(['reference' => 'PAY' . strtoupper(Str::random(10))]);
        }

        return redirect()->route('payment.confirmation', $booking_code);
    }

    // KF-07: halaman konfirmasi (status pembayaran + e-ticket)

public function confirmation(string $booking_code)
{
    Booking::releaseExpired();

    $bookings = $this->ownBookings($booking_code);
    $payment = Payment::where('booking_code', $booking_code)
        ->first();

    if (! $payment) {
        return redirect()->route('payment.show', $booking_code);
    }

    $schedule = $bookings->first()->schedule;

    return view('tickets.show', [
        'bus' => [
            'id'     => $schedule->id,
            'name'   => $schedule->nama_bus,
            'class'  => 'Reguler',
            'depart' => $schedule->waktu_keberangkatan->format('H:i'),
            'arrive' => $schedule->waktu_tiba
                ? $schedule->waktu_tiba->format('H:i')
                : '-',
        ],
        'query' => [
            'from' => $schedule->kota_asal,
            'to'   => $schedule->kota_tujuan,
            'date' => $schedule->waktu_keberangkatan->format('Y-m-d'),
        ],
        'seats' => $bookings->map(
            fn ($booking) => $booking->seat->seat_number
        )->all(),
        'code'   => $booking_code,
        'status' => $payment->status === 'paid'
            ? 'success'
            : $payment->status,
        'total'  => $bookings->sum('price'),
    ]);
}


    // KF-07: callback dari payment gateway (tanpa login, dijaga token rahasia)
    public function callback(Request $request)
    {
        $token = (string) config('services.payment.callback_token');

        abort_unless(
            $token !== '' && hash_equals($token, (string) $request->header('X-Callback-Token')),
            403
        );

        $data = $request->validate([
            'reference' => ['required', 'string'],
            'status'    => ['required', Rule::in(['success', 'failed', 'expired'])],
        ]);

        $payment = Payment::where('reference', $data['reference'])->firstOrFail();

        if ($data['status'] === 'success') {
            $payment->markAsPaid();
        } else {
            $payment->markAsFailed($data['status']);
        }

        return response()->json(['message' => 'OK']);
    }

    // KF-07: simulasi bayar berhasil, HANYA environment local
    public function simulate(string $booking_code)
    {
        abort_unless(app()->environment('local'), 404);

        Booking::releaseExpired();
        $this->ownBookings($booking_code);

      $payment = Payment::where('booking_code', $booking_code)
        ->where('user_id', Auth::id())
        ->firstOrFail();
        $payment->markAsPaid();

        return redirect()->route('payment.confirmation', $booking_code);
    }

    // Hanya pemilik pesanan yang boleh melihat/membayar
    private function ownBookings(string $booking_code)
    {
        $bookings = Booking::with([
            'schedule',
            'seat',
            'passenger',
            'ticket'
        ])
            ->where('booking_code', $booking_code)
            ->where('user_id', Auth::id())
            ->get();

        abort_if($bookings->isEmpty(), 404);

        return $bookings;
    }
}
