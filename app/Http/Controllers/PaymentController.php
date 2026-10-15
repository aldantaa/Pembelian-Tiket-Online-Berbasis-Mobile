<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public const METHODS = [
        'transfer_bank'   => 'Transfer Bank',
        'virtual_account' => 'Virtual Account',
        'e_wallet'        => 'E-Wallet',
        'qris'            => 'QRIS',
    ];

    // Halaman pembayaran
    public function show(string $booking_code)
    {
        Booking::releaseExpired();

        $bookings = $this->ownBookings($booking_code);
        $first = $bookings->first();

        if ($first->status === 'paid') {
            return redirect()->route('payment.confirmation', $booking_code);
        }

        return view('payment.show', [
            'booking_code' => $booking_code,
            'bookings'     => $bookings,
            'schedule'     => $first->schedule,
            'total'        => $bookings->sum('price'),
            'status'       => $first->status,
            'expiresAt'    => $first->expires_at,
            'methods'      => self::METHODS,
        ]);
    }

    // Proses pembayaran: buat/perbarui catatan pembayaran berstatus pending
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

    // KF-07: sementara placeholder
    public function confirmation(string $booking_code)
    {
        $this->ownBookings($booking_code);

        return view('payment.confirmation');
    }

    // Hanya pemilik pesanan yang boleh melihat/membayar
    private function ownBookings(string $booking_code)
    {
        $bookings = Booking::with(['schedule', 'seat', 'passenger'])
            ->where('booking_code', $booking_code)
            ->where('user_id', auth()->id())
            ->get();

        abort_if($bookings->isEmpty(), 404);

        return $bookings;
    }
}
