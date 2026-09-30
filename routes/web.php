<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

/*
|--------------------------------------------------------------------------
| Web Routes - Pembelian Tiket My Halte
|--------------------------------------------------------------------------
*/

// Root URL langsung merender halaman login
Route::get('/', [PageController::class, 'login'])->name('home.login');

// Alur Login
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::post('/login', [PageController::class, 'handleLogin'])->name('login.post');

// Alur Register
Route::get('/register', [PageController::class, 'register'])->name('register');
Route::post('/register', [PageController::class, 'handleRegister'])->name('register.post');

// Alur Logout
Route::post('/logout', [PageController::class, 'logout'])->name('logout');

// Jadwal & Tiket
Route::get('/schedules', [PageController::class, 'schedules'])->name('schedules');
Route::get('/tickets', [PageController::class, 'tickets'])->name('tickets.index');
Route::get('/tickets/{id}', [PageController::class, 'showTicket'])->name('tickets.show');
Route::get('/profile', [PageController::class, 'profile'])->name('profile');
