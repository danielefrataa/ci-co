<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\InputKodeController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\RoomListController;
use App\Http\Middleware\RoleRedirect;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\ProductionController;
use App\Models\Absen;

// udah fix jangan kerubah 
Route::get('/', function () {
    return view('welcome', [
        'absen' => Absen::all()
    ]);
})->name('dashboard');

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware(RoleRedirect::class);

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// front_office
Route::get('/front-office/dashboard', [LoginController::class, 'showFoDashboard'])->name('front_office.dashboard');
Route::get('/front-office/dashboard', [BookingsController::class, 'index'])->name('front_office.dashboard');
Route::post('/bookings/{id}/update-status', [BookingsController::class, 'updateStatus']);

Route::get('/bookings', [BookingsController::class, 'getBookingData']);

// register
Route::get('/front-office/register', [RegistrationController::class, 'showRegistrationForm'])->name('front_office.register');
Route::post('/front-office/register', [RegistrationController::class, 'register'])->name('front_office.register.post');

// dewint tambahin, untuk akses detail booking, checkin, dan peminjaman. 
Route::post('/store', [AbsenController::class, 'store'])->name('store');

Route::get('/booking/details/{kode_booking}', [BookingsController::class, 'showDetails'])->name('booking.details');
Route::post('/checkin/store', [AbsenController::class, 'checkinstore'])->name('checkin.store');
Route::get('/peminjaman/{kode_booking}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
Route::get('/front-office/inputkode', [InputKodeController::class, 'show'])->name('front_office.inputkode');

Route::get('/front-office/roomlist', [RoomListController::class, 'index'])->name('front_office.index');
Route::get('/front-office/roomlist', [RoomListController::class, 'filter'])->name('front_office.roomList');
// In routes/web.php

//marketing
Route::get('/marketing/peminjaman', [MarketingController::class, 'index'])->name('marketing.peminjaman');
Route::post('/marketing/store', [MarketingController::class, 'store'])->name('marketing.store');
//Route::get('/marketing/peminjaman', [BookingsController::class, 'index'])->name('front_office.dashboard');

//peminjaman
// Route untuk update dan tambah barang
Route::post('/peminjaman/update', [PeminjamanController::class, 'update'])->name('peminjaman.update');
Route::post('/peminjaman/store', [PeminjamanController::class, 'store'])->name('peminjaman.store');
Route::get('/peminjaman/create/{nama_event}', [PeminjamanController::class, 'showEdit'])->name('peminjaman.create');
Route::get('/bookings', [BookingsController::class, 'index'])->name('bookings.index');

Route::get('/front-office/inputkode', [InputKodeController::class, 'show'])->name('inputkode.show');
Route::post('/front-office/match', [InputKodeController::class, 'match'])->name('inputkode.match');
Route::post('/booking/complete-check-in/{kode_booking}', [InputKodeController::class, 'completeCheckIn'])->name('booking.completeCheckIn');

// production 
Route::get('/production/peminjaman', [ProductionController::class, 'index'])->name('production.peminjaman');

// Batas Terakhir dewinta yang ngerapihinnn