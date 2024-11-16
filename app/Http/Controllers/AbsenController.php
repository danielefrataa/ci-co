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

        Log::info('Kode Booking: ' . $request->id_booking);
        Log::info('Peminjaman: ' . ($peminjaman ? 'Ditemukan' : 'Tidak Ditemukan'));

        // Cek status booking
        $booking = Booking::where('kode_booking', $request->id_booking)
                          ->where('status', 'Approved') // Pastikan status booking Approved
                          ->first();

        if (!$booking) {
            // Jika booking tidak ditemukan atau statusnya bukan 'Approved', beri pesan dan redirect
            return redirect('/')->with('gagal', 'Booking tidak ditemukan atau statusnya bukan Approved.');
        }

        // Cek apakah user sudah pernah check-in berdasarkan id_booking dan statusnya
        $absen = Absen::where('id_booking', $request->id_booking)->first();

        if ($absen) {
            // Jika sudah ada dan statusnya 'Check-in', beri pesan bahwa check-in sudah dilakukan
            if ($absen->status == 'Check-in') {
                return redirect('/')->with('gagal', 'Anda sudah check-in sebelumnya.');
            }

            // Jika statusnya bukan 'Check-in', update statusnya
            $absen->update([
                'status' => 'Check-in',
                'tanggal' => now()->toDateString(), // Update tanggal jika diperlukan
            ]);
        } else {
            // Jika belum ada, buat catatan baru dengan status 'Check-in'
            Absen::create([
                'id_booking' => $request->id_booking,
                'tanggal' => now()->toDateString(),
                'status' => 'Check-in',
            ]);
        }

        // Simpan kode_booking ke sesi
        $request->session()->put('kode_booking', $booking->kode_booking);

        // Cek apakah ada peminjaman barang
        $peminjaman = PeminjamanBarang::where('kode_booking', $request->id_booking)->first();

        // Redirect ke halaman detail booking
        return redirect()->route('booking.details', ['kode_booking' => $booking->kode_booking])
            ->with('success', 'Check-in berhasil.');
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
            // Jika belum ada, buat catatan baru dengan status 'Check-in'
            Absen::create([
                'id_booking' => $kode_booking,
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'signature' => $request->input('signatureData'),
                'tanggal' => now()->toDateString(),
                'status' => 'Check-in', // Menambahkan status check-in
            ]);
        } else {
            // Jika sudah ada, update data yang diperlukan dan statusnya menjadi 'Check-in'
            $absen->update([
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'signature' => $request->input('signatureData'),
                'status' => 'Check-in', // Update status menjadi 'Check-in'
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
