<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController; // Wajib ditambahkan di Laravel 11

<<<<<<< HEAD
Route::get('/', [PageController::class, 'index']);
Route::get('/adminDashboard', [PageController::class, 'adminDashboard']);
Route::get('/booking', [PageController::class, 'booking']);
Route::get('/eTicket', [PageController::class, 'eTicket']);
Route::get('/landing', [PageController::class, 'landing']);
Route::get('/login', [PageController::class, 'login']);
Route::get('/orderHistory', [PageController::class, 'orderHistory']);
Route::get('/payment', [PageController::class, 'payment']);
Route::get('/profile', [PageController::class, 'profile']);
Route::get('/register', [PageController::class, 'register']);
Route::get('/search', [PageController::class, 'search']);
Route::get('/seatSelection', [PageController::class, 'seatSelection']);
Route::get('/home', [PageController::class, 'home']);
=======
Route::get('/', function () {
    return view('Search'); // sesuaikan nama file, perhatikan huruf besar/kecil
});
>>>>>>> origin/ui/ux
