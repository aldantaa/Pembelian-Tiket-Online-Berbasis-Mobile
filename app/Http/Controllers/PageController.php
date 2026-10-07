<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Tambahkan use model di bawah ini untuk menghilangkan peringatan kuning
use App\Models\Schedule;
use App\Models\Seat;

class PageController extends Controller
{
    // =========================================================================
    // 1. BAGIAN OTENTIKASI (LOGIN, REGISTER, LOGOUT)
    // =========================================================================

    public function login()
    {
        return view('auth.login');
    }

    public function handleLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
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
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat, silakan login!');
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


    // =========================================================================
    // 2. BAGIAN PEMESANAN (JADWAL, KURSI, PENUMPANG)
    // =========================================================================

    public function showSchedules()
    {
        // Mengambil semua data jadwal dari database
        $schedules = Schedule::all();
        return view('booking.schedules', compact('schedules'));
    }

    public function showSeatsBySchedule($schedule_id)
    {
         // Mencari jadwal berdasarkan ID, dan mengambil kursi yang terkait
         $schedule = Schedule::findOrFail($schedule_id);
         $seats = Seat::where('schedule_id', $schedule_id)->get();

         return view('booking.seats', compact('schedule', 'seats'));
    }

    public function passenger(Request $request)
    {
        // Halaman ini akan memproses data penumpang setelah memilih kursi
        return view('booking.passenger');
    }


    // =========================================================================
    // 3. BAGIAN DASBOR PENGGUNA (RIWAYAT TIKET & PROFIL)
    // =========================================================================

    public function tickets()
    {
        // Menampilkan daftar riwayat tiket yang pernah dibeli pengguna
        return view('tickets.index');
    }

    public function showTicket($id)
    {
        // Menampilkan detail dari satu tiket spesifik (misal: barcode, e-tiket)
        return view('tickets.show', compact('id'));
    }

    public function profile()
    {
        // Menampilkan halaman pengaturan profil pengguna (ubah nama, ubah password)
        return view('auth.profile');
    }
}
