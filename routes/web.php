<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Auth\FrontOfficeLoginController;
use App\Http\Controllers\InputKodeController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\BookingsController;

use App\Models\Absen;

Route::get('/', function () {
    return view('welcome', [  // Ganti . dengan koma
        'absen' => Absen::all()
    ]);
});

// front_office
Route::get('/front-office/dashboard', [FrontOfficeLoginController::class, 'showFoDashboard'])->name('front_office.dashboard');  

Route::get('/front-office/dashboard', [BookingsController::class, 'index'])->name('front_office.dashboard');





// Login
Route::get('/front-office/login', [FrontOfficeLoginController::class, 'showLoginForm'])->name('front_office.login');
Route::post('/front-office/login', [FrontOfficeLoginController::class, 'login'])->name('front_office.login.post');

// Logout
Route::post('/front-office/logout', [FrontOfficeLoginController::class, 'logout'])->name('front_office.logout');

// register
Route::get('/front-office/register', [RegistrationController::class, 'showRegistrationForm'])->name('front_office.register');
Route::post('/front-office/register', [RegistrationController::class, 'register'])->name('front_office.register.post');


Route::post('/store', [AbsenController::class, 'store'])->name('store');
//Route::get('/booking/details/{id}', [BookingController::class, 'showDetails'])->name('booking.details');
Route::get('/booking/details/{kode_booking}', [BookingController::class, 'showDetails'])->name('booking.details');
Route::post('/checkin/store', [AbsenController::class, 'store'])->name('checkin.store');

Route::get('/front-office/inputkode', [InputKodeController::class, 'show'])->name('front_office.inputkode');


Route::get('/bookings', [BookingsController::class, 'index'])->name('bookings.index');

