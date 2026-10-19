<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Booking;

class PageController extends Controller
{
    // =====================================================
    // 1. AUTENTIKASI
    // =====================================================

    public function login()
    {
        return view('auth.login');
    }

    public function handleLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        return redirect()->route('schedules');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function handleRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        return redirect()->route('login')
            ->with('success', 'Akun berhasil dibuat, silakan login!');
    }

    public function forgotPasswordForm()
    {
        return view('auth.forgotpassword');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();

        return redirect()->route('login');
    }

    // =====================================================
    // 2. JADWAL DAN PEMILIHAN KURSI
    // =====================================================

    
public function showSchedules(Request $request)
{
    $query = Schedule::query();

    // Filter berdasarkan kota asal jika diisi
    if ($request->filled('from')) {
        $query->whereRaw(
            'LOWER(kota_asal) LIKE ?',
            ['%' . strtolower(trim($request->from)) . '%']
        );
    }

    // Filter berdasarkan kota tujuan jika diisi
    if ($request->filled('to')) {
        $query->whereRaw(
            'LOWER(kota_tujuan) LIKE ?',
            ['%' . strtolower(trim($request->to)) . '%']
        );
    }

    // Filter berdasarkan tanggal jika diisi
    if ($request->filled('date')) {
        $query->whereDate(
            'waktu_keberangkatan',
            $request->date
        );
    }

    $schedules = $query
        ->orderBy('waktu_keberangkatan')
        ->get();

    return view('booking.schedules', compact('schedules'));
}


    public function showSeatsBySchedule($schedule_id)
    {
    Booking::releaseExpired(); // baris baru: lepas kursi dari pesanan yang kedaluwarsa

    $schedule = Schedule::findOrFail($schedule_id);

    // Kursi diurutkan A1, A2, A3, A4, B1, dan seterusnya.
    $seats = Seat::where('schedule_id', $schedule->id)
        ->orderByRaw(
            'LEFT(seat_number, 1),
            CAST(SUBSTRING(seat_number, 2) AS UNSIGNED)'
        )
        ->get();

    // Hitung kondisi kursi dari tabel seats.
    $totalSeats = $seats->count();

    $availableSeats = $seats
        ->where('is_booked', false)
        ->count();

    return view('booking.seats', [
        'schedule' => $schedule,
        'seats' => $seats,
        'totalSeats' => $totalSeats,
        'availableSeats' => $availableSeats,
        ]);
    }

    public function passenger(Request $request)
    {
        // Pemrosesan data penumpang merupakan KF-05.
        return view('booking.passenger');
    }

    // =====================================================
    // 3. RIWAYAT TIKET DAN PROFIL
    // =====================================================


    public function tickets()
    {
        return redirect()->route('orders.index');
    }


    public function showTicket($id)
    {
        return view('tickets.show', compact('id'));
    }

    public function profile()
    {
        return view('auth.profile');
    }
}
