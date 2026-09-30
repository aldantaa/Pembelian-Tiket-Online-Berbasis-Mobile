<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('/', function () {
    return view('Search'); // sesuaikan nama file, perhatikan huruf besar/kecil
});
Route::view('/register', 'auth.register')->name('register');
Route::view('/login', 'auth.login')->name('login');
Route::get('/jadwal', [PageController::class, 'schedules'])->name('schedules');
Route::get('/tiket-saya', [PageController::class, 'tickets'])->name('tickets');
