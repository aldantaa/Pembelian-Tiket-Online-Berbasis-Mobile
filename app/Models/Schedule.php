<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['origin', 'destination', 'departure_time', 'price'];

    // Satu jadwal memiliki banyak kursi
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}
