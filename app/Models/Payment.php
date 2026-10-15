<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Payment extends Model
{
    protected $fillable = [
        'booking_code', 'user_id', 'method', 'amount', 'status', 'reference', 'paid_at',
    ];

    protected function casts(): array
    {
        return ['paid_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'booking_code', 'booking_code');
    }

    // Pembayaran berhasil: booking jadi paid, e-ticket terbit, notifikasi dibuat
    public function markAsPaid(): void
    {
        DB::transaction(function () {
            $payment = static::whereKey($this->id)->lockForUpdate()->first();

            if ($payment->status === 'success') {
                return; // callback ganda tidak boleh menerbitkan tiket dua kali
            }

            $bookings = Booking::where('booking_code', $payment->booking_code)
                ->lockForUpdate()
                ->get();

            // pesanan sudah kedaluwarsa/dibatalkan: jangan terbitkan tiket
            if ($bookings->isEmpty() || $bookings->contains(fn ($b) => $b->status !== 'pending')) {
                return;
            }

            $payment->update(['status' => 'success', 'paid_at' => now()]);

            foreach ($bookings as $booking) {
                $booking->update(['status' => 'paid', 'expires_at' => null]);

                Ticket::firstOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'payment_id'  => $payment->id,
                        'ticket_code' => 'TKT' . strtoupper(Str::random(10)),
                        'issued_at'   => now(),
                    ]
                );
            }

            Alert::create([
                'user_id'      => $payment->user_id,
                'booking_code' => $payment->booking_code,
                'type'         => 'payment',
                'title'        => 'Pembayaran berhasil',
                'message'      => 'Pembayaran untuk pesanan ' . $payment->booking_code . ' berhasil. E-ticket sudah terbit.',
            ]);
        });

        $this->refresh();
    }

    // Pembayaran gagal/kedaluwarsa: booking dibatalkan, kursi dibuka lagi
    public function markAsFailed(string $status = 'failed'): void
    {
        DB::transaction(function () use ($status) {
            $payment = static::whereKey($this->id)->lockForUpdate()->first();

            if ($payment->status === 'success') {
                return;
            }

            $payment->update(['status' => $status]);

            foreach (Booking::where('booking_code', $payment->booking_code)->get() as $booking) {
                $booking->update(['status' => $status === 'expired' ? 'expired' : 'cancelled']);
                Seat::whereKey($booking->seat_id)->update(['is_booked' => false]);
            }

            Alert::create([
                'user_id'      => $payment->user_id,
                'booking_code' => $payment->booking_code,
                'type'         => 'payment',
                'title'        => $status === 'expired' ? 'Pembayaran kedaluwarsa' : 'Pembayaran gagal',
                'message'      => 'Pembayaran untuk pesanan ' . $payment->booking_code . ' tidak berhasil. Silakan pesan ulang.',
            ]);
        });

        $this->refresh();
    }
}
