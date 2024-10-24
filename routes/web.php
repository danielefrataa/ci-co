<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Auth\FrontOfficeLoginController;
use App\Http\Controllers\InputKodeController;

use App\Models\Absen;

Route::get('/', function () {
    return view('welcome', [  // Ganti . dengan koma
        'absen' => Absen::all()
    ]);
});
// Protected route for Front Office Dashboard
Route::middleware('auth')->group(function () {
    Route::get('/front-office/dashboard', function () {
        return view('front_office.dashboard');
    })->name('front_office.dashboard');
});

// front_office
Route::get('/front-office/login', [FrontOfficeLoginController::class, 'showLoginForm'])->name('front_office.login');
Route::post('/front-office/login', [FrontOfficeLoginController::class, 'login']);
Route::post('/front-office/logout', [FrontOfficeLoginController::class, 'logout'])->name('front_office.logout');


Route::post('/store', [AbsenController::class, 'store'])->name('store');
Route::get('/booking/details/{id}', [BookingController::class, 'showDetails'])->name('booking.details');
Route::get('/booking/details/{kode_booking}', [BookingController::class, 'showDetails'])->name('booking.details');
Route::post('/checkin/store', [AbsenController::class, 'store'])->name('checkin.store');

Route::get('/front-office/inputkode', [InputKodeController::class, 'show'])->name('front_office.inputkode');
//Route::get('/front-office/inputkode', [InputKodeController::class, 'match']);
