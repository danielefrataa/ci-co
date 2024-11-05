<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen;
use App\Models\Booking;
use App\Models\PeminjamanBarang;
use Illuminate\Support\Facades\Log;

class AbsenController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input data
        $request->validate([
            'id_booking' => 'required|string', // Asumsikan ini adalah kode_booking dari scan
        ]);

        // Cek peminjaman barang berdasarkan id_booking
        $peminjaman = PeminjamanBarang::where('kode_booking', $request->id_booking)->first();
        // Debug log
        Log::info('Kode Booking: ' . $request->id_booking);
        Log::info('Peminjaman: ' . ($peminjaman ? 'Ditemukan' : 'Tidak Ditemukan'));
        if (!$peminjaman) {
            // Jika peminjaman tidak ditemukan, beri pesan dan redirect
            return redirect('/')->with('gagal', 'Peminjaman tidak ditemukan. Anda tidak dapat check-in.');
        } {
            // Validasi input data
            $request->validate([
                'id_booking' => 'required|string', // Asumsikan ini adalah kode_booking dari scan
            ]);

            // Cek apakah user sudah pernah check-in berdasarkan id_booking
            $cek = Absen::where('id_booking', $request->id_booking)->first();

            if ($cek) {
                // Jika data check-in sudah ada, beri pesan bahwa check-in sudah pernah dilakukan
                return redirect('/')->with('gagal', 'Anda sudah pernah check-in sebelumnya.');
            }

            // Buat catatan check-in baru
            Absen::create([
                'id_booking' => $request->id_booking,
                'tanggal' => now()->toDateString(),
            ]);

            // Dapatkan detail booking berdasarkan kode_booking
            $booking = Booking::where('kode_booking', $request->id_booking)->first();

            if (!$booking) {
                return redirect('/')->with('gagal', 'Booking tidak ditemukan.');
            }

            // Simpan kode_booking ke sesi
            $request->session()->put('kode_booking', $booking->kode_booking);

            // Cek apakah ada peminjaman barang
            $peminjaman = PeminjamanBarang::where('kode_booking', $request->id_booking)->first();

            // Redirect ke halaman detail booking
            return redirect()->route('booking.details', ['kode_booking' => $booking->kode_booking])
                ->with('success', 'Check-in berhasil.');
        }
    }

    public function checkinstore(Request $request)
    {
        // Validasi data form
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'signatureData' => 'required',
        ]);

        // Ambil kode_booking dari sesi
        $kode_booking = $request->session()->get('kode_booking');

        // Cek apakah user sudah pernah check-in
        $absen = Absen::where('id_booking', $kode_booking)->first();

        if (!$absen) {
            // Jika belum ada, buat catatan baru
            Absen::create([
                'id_booking' => $kode_booking,
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'signature' => $request->input('signatureData'),
                'tanggal' => now()->toDateString(),
            ]);
        } else {
            // Jika sudah ada, update data yang diperlukan
            $absen->update([
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'signature' => $request->input('signatureData'),
            ]);
        }

        // Cek apakah ada peminjaman barang
        $peminjaman = PeminjamanBarang::where('kode_booking', $kode_booking)->first();

        // Redirect sesuai dengan kondisi peminjaman barang
        if ($peminjaman) {
            Log::info('Peminjaman ditemukan: ', $peminjaman->toArray());
            return redirect()->route('peminjaman.show', $peminjaman->kode_booking);
        } else {
            Log::info('Tidak ada peminjaman untuk kode_booking: ' . $kode_booking);
            return redirect()->route('dashboard')->with('success', 'Check-in berhasil tanpa peminjaman barang.');
        }
    }
}
