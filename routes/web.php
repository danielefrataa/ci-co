<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\BookingController;

use App\Models\Absen;
Route::get('/', function () {
    return view('welcome', [  // Ganti . dengan koma
        'absen' => Absen::all()
    ]);
});

Route::post('/store', [AbsenController::class, 'store'])->name('store');
Route::get('/booking/details/{id}', [BookingController::class, 'showDetails'])->name('booking.details');
Route::get('/booking/details/{kode_booking}', [BookingController::class, 'showDetails'])->name('booking.details');
Route::post('/checkin/store', [AbsenController::class, 'store'])->name('checkin.store');
