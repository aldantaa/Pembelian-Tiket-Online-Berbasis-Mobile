<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    private const MAX_SEATS = 5;
    private const HOLD_MINUTES = 30;

    // Halaman data penumpang (setelah memilih kursi)
    public function passenger(Request $request)
    {
        Booking::releaseExpired();

        $schedule = Schedule::find($request->input('schedule_id', $request->input('bus')));
        if (! $schedule) {
            return redirect()->route('schedules')->with('error', 'Jadwal tidak ditemukan.');
        }

        $seatNumbers = $this->parseSeatNumbers((string) $request->input('seats', ''));

        if (count($seatNumbers) < 1 || count($seatNumbers) > self::MAX_SEATS) {
            return redirect()->route('booking.seats', $schedule->id)
                ->with('error', 'Pilih 1 sampai ' . self::MAX_SEATS . ' kursi.');
        }

        $seats = Seat::where('schedule_id', $schedule->id)
            ->whereIn('seat_number', $seatNumbers)
            ->orderByRaw('LEFT(seat_number, 1), CAST(SUBSTRING(seat_number, 2) AS UNSIGNED)')
            ->get();

        if ($seats->count() !== count($seatNumbers) || $seats->contains('is_booked', true)) {
            return redirect()->route('booking.seats', $schedule->id)
                ->with('error', 'Ada kursi yang tidak tersedia. Silakan pilih ulang.');
        }

        $total = $seats->count() * (int) $schedule->harga;

        return view('booking.passenger', compact('schedule', 'seats', 'total'));
    }

    // Simpan pemesanan: booking + penumpang + kunci kursi
    public function store(Request $request)
    {
        $data = $request->validate([
            'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
            'seats' => ['required', 'string'],
            'passengers' => ['required', 'array'],
            'passengers.*.name' => ['required', 'string', 'max:100'],
            'passengers.*.id_number' => ['nullable', 'string', 'max:30'],
            'passengers.*.phone' => ['nullable', 'string', 'max:20'],
        ]);

        $seatNumbers = $this->parseSeatNumbers($data['seats']);

        if (count($seatNumbers) < 1 || count($seatNumbers) > self::MAX_SEATS) {
            throw ValidationException::withMessages([
                'seats' => 'Jumlah kursi harus 1 sampai ' . self::MAX_SEATS . '.',
            ]);
        }

        foreach ($seatNumbers as $number) {
            if (! isset($data['passengers'][$number])) {
                throw ValidationException::withMessages([
                    'passengers' => 'Data penumpang untuk kursi ' . $number . ' belum diisi.',
                ]);
            }
        }

        Booking::releaseExpired();
        $userId = $request->user()->id;

        $code = DB::transaction(function () use ($data, $seatNumbers, $userId) {
            $schedule = Schedule::findOrFail($data['schedule_id']);

            // kunci baris kursi agar dua orang tidak bisa memesan kursi yang sama
            $seats = Seat::where('schedule_id', $schedule->id)
                ->whereIn('seat_number', $seatNumbers)
                ->lockForUpdate()
                ->get();

            if ($seats->count() !== count($seatNumbers) || $seats->contains('is_booked', true)) {
                throw ValidationException::withMessages([
                    'seats' => 'Maaf, ada kursi yang sudah dipesan orang lain. Silakan pilih ulang.',
                ]);
            }

            $code = 'BK' . strtoupper(Str::random(8));

            foreach ($seats as $seat) {
                $booking = Booking::create([
                    'booking_code' => $code,
                    'user_id' => $userId,
                    'schedule_id' => $schedule->id,
                    'seat_id' => $seat->id,
                    'price' => (int) $schedule->harga,
                    'status' => 'pending',
                    'expires_at' => now()->addMinutes(self::HOLD_MINUTES),
                ]);

                $p = $data['passengers'][$seat->seat_number];

                $booking->passenger()->create([
                    'name' => $p['name'],
                    'id_number' => $p['id_number'] ?? null,
                    'phone' => $p['phone'] ?? null,
                ]);

                $seat->update(['is_booked' => true]);
            }

            return $code;
        });

        return redirect()->route('payment.show', $code)
            ->with('success', 'Pemesanan dibuat. Selesaikan pembayaran dalam ' . self::HOLD_MINUTES . ' menit.');
    }

    // "A1, a2 ,A1" -> ['A1', 'A2']
    private function parseSeatNumbers(string $raw): array
    {
        return collect(explode(',', $raw))
            ->map(fn ($s) => strtoupper(trim($s)))
            ->filter(fn ($s) => preg_match('/^[A-Z][0-9]{1,2}$/', $s))
            ->unique()
            ->values()
            ->all();
    }
}
