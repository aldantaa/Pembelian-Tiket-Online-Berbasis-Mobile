<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = [
        'booking_code', 'user_id', 'schedule_id', 'seat_id',
        'price', 'status', 'expires_at',
    ];

    protected function casts(): array
    {
        return ['expires_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }

    public function passenger(): HasOne
    {
        return $this->hasOne(Passenger::class);
    }

    // Booking pending yang lewat batas bayar dibatalkan, kursinya dibuka lagi
    public static function releaseExpired(): void
    {
        $expired = static::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($expired as $booking) {
            $booking->update(['status' => 'expired']);
            Seat::whereKey($booking->seat_id)->update(['is_booked' => false]);
        }
    }
}
