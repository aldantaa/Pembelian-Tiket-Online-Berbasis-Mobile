
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\UserTransactionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\OrderHistoryController;
use App\Http\Controllers\Admin\AlertController;

/*
|--------------------------------------------------------------------------
| HALAMAN AWAL DAN AUTENTIKASI
|--------------------------------------------------------------------------
*/

Route::get('/', [PageController::class, 'login'])->name('home.login');

Route::get('/login', [PageController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/forgotpassword', [
    PageController::class,
    'forgotPasswordForm',
])->name('password.request');

Route::post('/forgotpassword', [
    PageController::class,
    'forgotPasswordForm',
])->name('password.update');

Route::get('/register', [PageController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [PageController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Dashboard dan laporan
    Route::get('/dashboard', [ReportController::class, 'dashboard'])
        ->name('admin.dashboard');

    Route::get('/export', [UserTransactionController::class, 'export'])
    ->name('admin.export');

    // Pengelolaan jadwal bus
    Route::get('/schedules', [ScheduleController::class, 'index'])
        ->name('admin.schedules');

    Route::post('/schedules', [ScheduleController::class, 'store'])
        ->name('admin.schedules.store');

    Route::put('/schedules/{id}', [ScheduleController::class, 'update'])
        ->whereNumber('id')
        ->name('admin.schedules.update');

    Route::delete('/schedules/{id}', [ScheduleController::class, 'delete'])
        ->whereNumber('id')
        ->name('admin.schedules.delete');

    // Pengelolaan pengguna
    Route::get('/users', [UserTransactionController::class, 'users'])
        ->name('admin.users');

    Route::get('/user', [UserTransactionController::class, 'users'])
        ->name('admin.user');

    Route::get('/users/{id}/edit', [UserTransactionController::class, 'editUser'])
        ->whereNumber('id')
        ->name('admin.users.edit');

    Route::put('/users/{id}', [UserTransactionController::class, 'updateUser'])
        ->whereNumber('id')
        ->name('admin.users.update');

    Route::delete('/users/{id}', [UserTransactionController::class, 'deleteUser'])
        ->whereNumber('id')
        ->name('admin.users.delete');

    // Riwayat pemesanan
    Route::get('/orders', [OrderHistoryController::class, 'index'])
        ->name('admin.orders');

    // Notifikasi dan peringatan
    Route::get('/alerts', [AlertController::class, 'index'])
        ->name('admin.alerts');

    // Riwayat transaksi (hanya menampilkan data)
    Route::get('/transactions', [UserTransactionController::class, 'transactions'])
        ->name('admin.transactions');
});


/*
|--------------------------------------------------------------------------
| JADWAL BUS UNTUK PENGGUNA
|--------------------------------------------------------------------------
*/

Route::get('/schedules', [PageController::class, 'showSchedules'])
    ->name('schedules');

Route::get('/schedules/{schedule_id}/seats', [
    PageController::class,
    'showSeatsBySchedule',
])->whereNumber('schedule_id')->name('booking.seats');


/*
|--------------------------------------------------------------------------
| CALLBACK PEMBAYARAN
|--------------------------------------------------------------------------
*/

Route::post('/payment/callback', [PaymentController::class, 'callback'])
    ->name('payment.callback');


/*
|--------------------------------------------------------------------------
| FITUR PENGGUNA YANG MEMERLUKAN LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/passenger', [BookingController::class, 'passenger'])
        ->name('passenger');

    Route::post('/bookings', [BookingController::class, 'store'])
        ->name('bookings.store');

    Route::get('/payment/{booking_code}', [PaymentController::class, 'show'])
        ->name('payment.show');

    Route::post('/payment/{booking_code}/pay', [PaymentController::class, 'pay'])
        ->name('payment.pay');

    Route::get('/payment/{booking_code}/confirmation', [
        PaymentController::class,
        'confirmation',
    ])->name('payment.confirmation');

    if (app()->environment('local')) {
        Route::post('/payment/{booking_code}/simulate', [
            PaymentController::class,
            'simulate',
        ])->name('payment.simulate');
    }

    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');

    Route::get('/orders/{booking_code}', [OrderController::class, 'show'])
        ->name('orders.show');

    Route::get('/profile', [PageController::class, 'profile'])
        ->name('profile');

    Route::get('/tickets', [PageController::class, 'tickets'])
        ->name('tickets.index');

    Route::get('/tickets/{id}', [PageController::class, 'showTicket'])
        ->name('tickets.show');
});
