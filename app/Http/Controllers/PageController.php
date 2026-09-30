<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Registrasi, login, logout, dan profil.
 * Sementara memakai session; ganti dengan Auth Laravel + tabel penumpang saat DB siap.
 */
class AuthController extends Controller
{
<<<<<<< Updated upstream
    public function index() {
        return view('home');
    }

    public function about() {
        return view('about');
    }

    public function service() {
        return view('service');
    }
}
=======
    public function register(Request $r)
    {
        return redirect()->route('login')->with('success', 'Akun berhasil dibuat. Silakan masuk.');
    }

    public function login(Request $r)
    {
        $email = $r->input('email');

        session(['user' => [
            'name'  => session('user.name') ?? ucfirst(Str::before($email, '@')),
            'email' => $email,
            'phone' => session('user.phone', ''),
        ]]);

        return redirect()->route('schedules')->with('success', 'Berhasil masuk.');
    }

    public function logout()
    {
        session()->forget('user');

        return redirect()->route('login')->with('success', 'Anda telah keluar.');
    }

    public function profile()
    {
        if (! session()->has('user')) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        return view('auth.profile', ['user' => session('user')]);
    }

    public function updateProfile(Request $r)
    {
        session(['user' => $r->only('name', 'email', 'phone')]);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }

     public function schedules(Request $r)
    {
        return view('booking.schedules', [
            'buses' => $r->filled('from') ? $this->buses() : [],
            'query' => $r->only('from', 'to', 'date'),
        ]);
    }

    public function ticket(Request $r)
    {
        return view('tickets.show', $this->bookingContext($r) + ['code' => 'MH90801128']);
    }


    private function buses(): array
    {
        return [
            ['id' => 1, 'name' => 'Sinar Jaya',    'class' => 'Executive',       'price' => 350000, 'depart' => '08:00', 'arrive' => '18:00', 'facilities' => ['WiFi', 'AC', 'USB Charger', 'Snack'], 'available' => 15, 'capacity' => 40],
            ['id' => 2, 'name' => 'Harapan Jaya',  'class' => 'Super Executive', 'price' => 350000, 'depart' => '14:00', 'arrive' => '00:00', 'facilities' => ['WiFi', 'AC', 'Toilet'],               'available' => 22, 'capacity' => 40],
            ['id' => 3, 'name' => 'Rosalia Indah', 'class' => 'Executive',       'price' => 320000, 'depart' => '20:00', 'arrive' => '06:00', 'facilities' => ['AC', 'USB Charger'],                 'available' => 9,  'capacity' => 40],
        ];
    }

    private function findBus(int $id): array
    {
        return collect($this->buses())->firstWhere('id', $id) ?? $this->buses()[0];
    }
    
    private function bookingContext(Request $r): array
    {
        $bus   = $this->findBus((int) $r->query('bus', 1));
        $seats = array_values(array_filter(explode(',', $r->query('seats', 'H1,H2'))));

        return ['bus' => $bus, 'seats' => $seats, 'total' => $bus['price'] * count($seats)];
    }
}
>>>>>>> Stashed changes
