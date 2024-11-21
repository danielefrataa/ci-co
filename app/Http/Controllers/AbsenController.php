<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen;
use App\Models\PeminjamanBarang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class AbsenController extends Controller
{
    private $apiKey = 'JUrrUHAAdBepnJjpfVL2nY6mx9x4Cful4AhYxgs3Qj6HEgryn77KOoDr6BQZgHU1';

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_booking' => 'required|string',
        ]);

        $id_booking = strtolower(trim($request->id_booking));

        // Panggil API
        $apiUrl = "https://event.mcc.or.id/api/event?status=booked";
        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
        ])->withoutVerifying()->get($apiUrl);

        if (!$response->successful()) {
            return redirect('/')->with('gagal', 'Gagal mengambil data booking dari API.');
        }

        // Ambil data booking berdasarkan 'booking_code'
        $bookingData = collect($response->json()['data'])->firstWhere('booking_code', $id_booking);

        if (!$bookingData) {
            return redirect('/')->with('gagal', 'Booking tidak ditemukan.');
        }

        // Log data untuk debugging
        Log::info('Data Booking dari API: ', $bookingData);

        // Cek apakah user sudah check-in
        $absen = Absen::where('id_booking', $id_booking)->first();

        if ($absen) {
            if ($absen->status == 'Check-in') {
                return redirect('/')->with('gagal', 'Anda sudah check-in sebelumnya.');
            }

            $absen->update([
                'status' => 'Check-in',
                'tanggal' => now()->toDateString(),
            ]);
        } else {
            Absen::create([
                'id_booking' => $id_booking,
                'tanggal' => now()->toDateString(),
                'status' => 'Check-in',
            ]);
        }

        // Simpan kode_booking ke sesi
        $request->session()->put('kode_booking', $id_booking);

        // Cek apakah ada peminjaman barang
        $peminjaman = PeminjamanBarang::where('kode_booking', $id_booking)->first();

        return redirect()->route('booking.details', ['kode_booking' => $id_booking])
            ->with('success', 'Check-in berhasil.');
    }

    public function checkinstore(Request $request)
    {
        // Validasi input form check-in
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'signatureData' => 'required',
        ]);

        // Ambil kode_booking dari sesi
        $kode_booking = $request->session()->get('kode_booking');

        // Pastikan booking ada di API
        $apiUrl = "https://event.mcc.or.id/api/event?status=booked";
        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
        ])->withoutVerifying()->get($apiUrl);

        if (!$response->successful()) {
            return redirect('/')->with('gagal', 'Gagal mengambil data booking dari API.');
        }

        $bookingData = collect($response->json()['data'])->firstWhere('booking_code', $kode_booking);

        if (!$bookingData) {
            return redirect('/')->with('gagal', 'Booking tidak ditemukan.');
        }

        // Cek apakah absen sudah ada
        $absen = Absen::where('id_booking', $kode_booking)->first();

        if (!$absen) {
            Absen::create([
                'id_booking' => $kode_booking,
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'signature' => $request->input('signatureData'),
                'tanggal' => now()->toDateString(),
                'status' => 'Check-in',
            ]);
        } else {
            $absen->update([
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'signature' => $request->input('signatureData'),
                'status' => 'Check-in',
            ]);
        }

        // Cek apakah ada peminjaman barang
        $peminjaman = PeminjamanBarang::where('kode_booking', $kode_booking)->first();

        if ($peminjaman) {
            Log::info('Peminjaman ditemukan: ', $peminjaman->toArray());
            return redirect()->route('peminjaman.show', $peminjaman->kode_booking);
        } else {
            Log::info('Tidak ada peminjaman untuk kode_booking: ' . $kode_booking);
            return redirect()->route('dashboard')->with('success', 'Check-in berhasil tanpa peminjaman barang.');
        }
    }
}
