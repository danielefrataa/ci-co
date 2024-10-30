<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeminjamanBarang;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

class PeminjamanController extends Controller
{
    public function show($kode_booking)
    {
        // Log awal untuk memastikan metode dipanggil
        Log::info("Memulai pengecekan untuk kode booking: {$kode_booking}");

        // Ambil semua data peminjaman berdasarkan kode_booking
        $peminjamans = PeminjamanBarang::where('kode_booking', $kode_booking)->get();

        if ($peminjamans->isEmpty()) {
            Log::warning("Peminjaman tidak ditemukan untuk kode_booking: {$kode_booking}");
            return redirect('/')->with('error', 'Peminjaman tidak ditemukan.');
        }

        Log::info("Peminjaman ditemukan: ", ['peminjamans' => $peminjamans]);

        // Ambil data booking terkait peminjaman (jika tersedia)
        $booking = Booking::where('kode_booking', $kode_booking)->first();

        if (!$booking) {
            Log::warning("Data booking tidak ditemukan untuk kode_booking: {$kode_booking}");
            return redirect('/')->with('error', 'Booking tidak ditemukan.');
        }

        Log::info("Booking ditemukan: ", ['booking' => $booking]);

        // Siapkan data tambahan untuk ditampilkan di view
        $data = [
            'peminjamans' => $peminjamans,
            'nama_event' => $booking->nama_event ?? 'Tidak Tersedia',
            'ruangan' => $booking->ruangan ?? 'Tidak Tersedia',
            'pic' => $booking->nama_pic ?? 'Tidak Tersedia',
            'tanggal' => $booking->tanggal ?? 'Tidak Tersedia',
            'jam' => $booking->jam ?? 'Tidak Tersedia',
        ];

        Log::info("Data untuk ditampilkan di view: ", $data);

        // Tampilkan view dengan data peminjaman
        return view('booking.showPinjam', $data);
    }
}
