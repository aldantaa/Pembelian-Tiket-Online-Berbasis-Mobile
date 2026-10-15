
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes - Pembelian Tiket My Halte
|--------------------------------------------------------------------------
*/

// =====================================================
// HALAMAN AWAL
// =====================================================

Route::get('/', [PageController::class, 'login'])
    ->name('home.login');

// =====================================================
// AUTENTIKASI
// =====================================================

// Login
Route::get('/login', [PageController::class, 'login'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

// Lupa password
Route::get('/forgotpassword', [
    PageController::class,
    'forgotPasswordForm'
])->name('password.request');

Route::post('/forgotpassword', [
    PageController::class,
    'forgotPasswordForm'
])->name('password.update');

// Register
Route::get('/register', [PageController::class, 'register'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.post');

// Logout
Route::post('/logout', [PageController::class, 'logout'])
    ->name('logout');

// =====================================================
// KF-03: JADWAL BUS
// =====================================================

Route::get('/schedules', [
    PageController::class,
    'showSchedules'
])->name('schedules');

// =====================================================
// KF-04: PEMILIHAN KURSI
// =====================================================

Route::get('/schedules/{schedule_id}/seats', [
    PageController::class,
    'showSeatsBySchedule'
])
    ->whereNumber('schedule_id')
    ->name('booking.seats');

// =====================================================
// KF-07: CALLBACK PEMBAYARAN
// Di luar middleware auth, dilindungi token callback.
// =====================================================

Route::post('/payment/callback', [
    PaymentController::class,
    'callback'
])->name('payment.callback');

// =====================================================
// KF-05, KF-06, KF-07, KF-11
// ROUTE YANG MEMERLUKAN LOGIN
// =====================================================

Route::middleware('auth')->group(function () {

    // KF-05: Pemesanan tiket
    Route::get('/passenger', [
        BookingController::class,
        'passenger'
    ])->name('passenger');

    Route::post('/bookings', [
        BookingController::class,
        'store'
    ])->name('bookings.store');

    // KF-06: Transaksi pembayaran
    Route::get('/payment/{booking_code}', [
        PaymentController::class,
        'show'
    ])->name('payment.show');

    Route::post('/payment/{booking_code}/pay', [
        PaymentController::class,
        'pay'
    ])->name('payment.pay');

    // KF-07: Konfirmasi pembayaran
    Route::get('/payment/{booking_code}/confirmation', [
        PaymentController::class,
        'confirmation'
    ])->name('payment.confirmation');

    // Simulasi pembayaran hanya di lingkungan local
    if (app()->environment('local')) {
        Route::post('/payment/{booking_code}/simulate', [
            PaymentController::class,
            'simulate'
        ])->name('payment.simulate');
    }

    // KF-11: Riwayat pesanan
    Route::get('/orders', [
        OrderController::class,
        'index'
    ])->name('orders.index');

    // KF-11: Detail pesanan berdasarkan booking_code
    Route::get('/orders/{booking_code}', [
        OrderController::class,
        'show'
    ])->name('orders.show');

    // Profil
    Route::get('/profile', [
        PageController::class,
        'profile'
    ])->name('profile');

    // Route tiket lama dipertahankan agar kompatibel
    // dengan tautan di view yang sudah ada.
    Route::get('/tickets', [
        PageController::class,
        'tickets'
    ])->name('tickets.index');

    Route::get('/tickets/{id}', [
        PageController::class,
        'showTicket'
    ])->name('tickets.show');
});
