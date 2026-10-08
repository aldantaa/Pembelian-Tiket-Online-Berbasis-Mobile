<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes - Pembelian Tiket My Halte
|--------------------------------------------------------------------------
*/

// Root URL langsung merender halaman login
Route::get('/', [PageController::class, 'login'])->name('home.login');

// Alur Login
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Alur Lupa Password
Route::get('/forgotpassword', [PageController::class, 'forgotPasswordForm'])->name('password.request');
Route::post('/forgotpassword', [PageController::class, 'forgotPasswordForm'])->name('password.update');

// Alur Register
Route::get('/register', [PageController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Alur Logout
Route::post('/logout', [PageController::class, 'logout'])->name('logout');

Route::get('/schedules', [PageController::class, 'showSchedules'])->name('schedules');

// 2. Tambahkan rute untuk menampilkan kursi berdasarkan jadwal yang dipilih
Route::get('/schedules/{schedule_id}/seats', [PageController::class, 'showSeatsBySchedule'])->name('booking.seats');

// Rute untuk halaman detail penumpang setelah memilih kursi
Route::get('/passenger', [PageController::class, 'passenger'])->name('passenger');

// Jadwal & Tiket
Route::get('/tickets', [PageController::class, 'tickets'])->name('tickets.index');
Route::get('/tickets/{id}', [PageController::class, 'showTicket'])->name('tickets.show');
Route::get('/profile', [PageController::class, 'profile'])->name('profile');
