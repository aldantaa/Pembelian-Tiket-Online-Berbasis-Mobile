<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Passenger extends Model
{
    protected $fillable = ['booking_id', 'name', 'id_number', 'phone'];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
