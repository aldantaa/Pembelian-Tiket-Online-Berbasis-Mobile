<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Seat;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat data jadwal dummy keberangkatan dari Madiun
        $schedule = Schedule::create([
            'origin' => 'Madiun',
            'destination' => 'Surabaya',
            'departure_time' => Carbon::tomorrow()->setHour(8)->setMinute(0),
            'price' => 75000,
        ]);

        // Generate 10 kursi otomatis (A1 sampai A10) untuk jadwal tersebut
        for ($i = 1; $i <= 10; $i++) {
            Seat::create([
                'schedule_id' => $schedule->id,
                'seat_number' => 'A' . $i,
                'is_booked' => false,
            ]);
        }
    }
}
