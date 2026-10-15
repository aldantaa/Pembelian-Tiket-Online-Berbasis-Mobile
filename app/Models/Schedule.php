<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    protected $fillable = [
        'nama_bus',
        'kota_asal',
        'kota_tujuan',
        'waktu_keberangkatan',
        'waktu_tiba',
        'harga',
        'kapasitas',
        'kursi_tersedia',
        'fasilitas',
    ];

    protected function casts(): array
    {
        return [
            'waktu_keberangkatan' => 'datetime',
            'waktu_tiba' => 'datetime',
            'harga' => 'integer',
            'kapasitas' => 'integer',
            'kursi_tersedia' => 'integer',
        ];
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    public function getTotalSeatsAttribute(): int
    {
        return $this->seats()->count();
    }

    public function getAvailableSeatsAttribute(): int
    {
        return $this->seats()
            ->where('is_booked', false)
            ->count();
    }
}
