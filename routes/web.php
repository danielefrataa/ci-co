<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Auth\FrontOfficeLoginController;
use App\Http\Controllers\InputKodeController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\RoomListController;
<<<<<<< Updated upstream

use App\Http\Controllers\PeminjamanController;

=======


use App\Http\Controllers\PeminjamanController;
>>>>>>> Stashed changes
use App\Models\Absen;

Route::get('/', function () {
    return view('welcome', [
        'absen' => Absen::all()
    ]);
})->name('dashboard');
// front_office
Route::get('/front-office/dashboard', [FrontOfficeLoginController::class, 'showFoDashboard'])->name('front_office.dashboard');
Route::get('/front-office/dashboard', [BookingsController::class, 'index'])->name('front_office.dashboard');
Route::post('/bookings/{id}/update-status', [BookingsController::class, 'updateStatus']);
Route::get('/bookings', [BookingController::class, 'getBookingData']);

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

// dewint tambahin, untuk akses detail booking, checkin, dan peminjaman. 
Route::post('/store', [AbsenController::class, 'store'])->name('store');
<<<<<<< Updated upstream

=======
>>>>>>> Stashed changes
//Route::get('/booking/details/{id}', [BookingController::class, 'showDetails'])->name('booking.details');
Route::get('/booking/details/{kode_booking}', [BookingController::class, 'showDetails'])->name('booking.details');
Route::post('/checkin/store', [AbsenController::class, 'checkinstore'])->name('checkin.store');
Route::get('/peminjaman/{kode_booking}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
Route::get('/front-office/inputkode', [InputKodeController::class, 'show'])->name('front_office.inputkode');
Route::get('/front-office/roomlist', [RoomListController::class, 'show'])->name('front_office.roomList');
<<<<<<< Updated upstream

=======
>>>>>>> Stashed changes

Route::get('/booking/details/{kode_booking}', [BookingController::class, 'showDetails'])->name('booking.details');
Route::post('/checkin/store', [AbsenController::class, 'checkinstore'])->name('checkin.store');
Route::get('/peminjaman/{kode_booking}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

Route::get('/front-office/inputkode', [InputKodeController::class, 'show'])->name('front_office.inputkode');

Route::get('/front-office/inputkode', [InputKodeController::class, 'show'])->name('front_office.inputkode');



Route::get('/bookings', [BookingsController::class, 'index'])->name('bookings.index');


Route::get('/bookings', [BookingsController::class, 'index'])->name('bookings.index');

Route::post('/front-office/inputkode', [InputKodeController::class, 'match'])->name('match');

Route::get('/front-office/roomlist', [RoomListController::class, 'show'])->name('front_office.roomList');

//Route::get('/front-office/inputkode', [InputKodeController::class, 'match']);

Route::get('/bookings', [BookingsController::class, 'index'])->name('bookings.index');


Route::post('/front-office/inputkode', [InputKodeController::class, 'match'])->name('match');

