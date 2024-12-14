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
use App\Http\Controllers\dinasApprovalController;
use App\Models\Absen;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\DutyOfficerController;
use App\Exports\BookingsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;


//Export Data
Route::get('export-bookings', function () {
    return Excel::download(new BookingsExport(request()->all()), 'bookings.csv');
})->name('bookings.export');


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
Route::get('/front-office/dashboard', [BookingsController::class, 'index'])->name('front_office.dashboard');
Route::post('/bookings/{id}/update-status', [BookingsController::class, 'updateStatus']);
Route::post('/update-duty-officer', [BookingsController::class, 'updateDutyOfficer'])
    ->middleware('auth') // Tambahkan middleware di sini
    ->name('update-duty-officer');

Route::get('/duty-officer/{id}', [Bookings::class, 'dutyOfficer'])->name('duty_officer');


// diskopindag
Route::get('/dinas/approve', [dinasApprovalController::class, 'index'])->name('dinas.approve');
Route::post('/approval/store', [dinasApprovalController::class, 'store'])->name('approval.store');

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
Route::delete('/items/{id}', [MarketingController::class, 'destroy'])->name('items.destroy');
//Route::get('/marketing/peminjaman', [BookingsController::class, 'index'])->name('front_office.dashboard');

//peminjaman
// Route untuk update dan tambah barang
Route::post('/peminjaman/update', [PeminjamanController::class, 'update'])->name('peminjaman.update');
Route::post('/peminjaman/store', [PeminjamanController::class, 'store'])->name('peminjaman.store');
Route::get('/peminjaman/create/{nama_event}', [PeminjamanController::class, 'showEdit'])->name('peminjaman.create');
Route::get('/bookings', [BookingsController::class, 'index'])->name('bookings.index');

Route::get('/front-office/inputkode', [InputKodeController::class, 'show'])->name('inputkode.show');
Route::match(['get', 'post'], '/front-office/match', [InputKodeController::class, 'match'])->name('inputkode.match');
Route::post('/booking/complete-check-in/{kode_booking}', [InputKodeController::class, 'completeCheckIn'])->name('booking.completeCheckIn');
Route::post('/checkout', [InputKodeController::class, 'checkout'])->name('inputkode.checkout');

// production 
Route::get('/production/peminjaman', [ProductionController::class, 'index'])->name('production.peminjaman');

// Batas Terakhir dewinta yang ngerapihinnn


Route::get('/generate-qrcode/{bookingCode}', [QRCodeController::class, 'generateQRCode']);
// Route::get('/generate-qrcode/{bookingCode}', [QRCodeController::class, 'sendQRCode']);
Route::post('/duty-officer/store', [DutyOfficerController::class, 'storeDutyOfficer'])->name('dutyofficer.store');
