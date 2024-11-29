<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeminjamanBarang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\Absen;
 // Pastikan model Absen telah diimpor
class PeminjamanController extends Controller
{
    private $apiKey = 'JUrrUHAAdBepnJjpfVL2nY6mx9x4Cful4AhYxgs3Qj6HEgryn77KOoDr6BQZgHU1';

    public function show(Request $request, $kode_booking)
    {
        $today = Carbon::now()->toDateString(); // Format: 2024-11-19

        // Ambil data booking dari API
        $url = "https://event.mcc.or.id/api/event?status=booked&booking_code={$kode_booking}";
        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
        ])->withoutVerifying()->get($url);

        if ($response->successful()) {
            $data = collect($response->json()['data'] ?? []);

            // Ambil data booking berdasarkan kode_booking
            $booking = $data->firstWhere('booking_code', $kode_booking);

            if ($booking) {
                // Filter booking_items sesuai dengan tanggal hari ini
                $bookingItems = collect($booking['booking_items'] ?? [])
                    ->filter(fn($bookingItem) => $bookingItem['booking_date'] === $today)
                    ->values();

                // Hitung waktu mulai dan selesai
                $startTime = $bookingItems->min('booking_hour');
                $endTime = $bookingItems->max('booking_hour');

                $booking['start_time'] = $startTime ? Carbon::createFromTime($startTime, 0)->format('H:i') : null;
                $booking['end_time'] = $endTime ? Carbon::createFromTime($endTime, 0)->format('H:i') : null;

                // Ambil data ruangan terkait
                $ruanganIds = $bookingItems->pluck('ruangan_id')->unique();
                $booking['ruangans'] = collect($booking['ruangans'] ?? [])
                    ->filter(fn($ruangan) => $ruanganIds->contains($ruangan['id']))
                    ->values()
                    ->toArray();

                // Ambil data peminjaman barang dari API
                $urlPeminjaman = "https://event.mcc.or.id/api/peminjaman?booking_code={$kode_booking}";
                $responsePeminjaman = Http::withHeaders([
                    'X-API-KEY' => $this->apiKey,
                ])->withoutVerifying()->get($urlPeminjaman);

                $databaseItems = [];
                if ($responsePeminjaman->successful()) {
                    $databaseItems = collect($responsePeminjaman->json()['data'] ?? []);
                } else {
                    report('Error accessing API for peminjaman: ' . $responsePeminjaman->status());
                }

                // Ambil data peminjaman dari database lokal
                $booking['database_items'] = PeminjamanBarang::where('kode_booking', $booking['booking_code'])->get();

                // Gabungkan data tools dari hasil API
                $tools = collect($response->json()['data'] ?? [])
                    ->where('booking_code', $kode_booking)
                    ->pluck('tools')
                    ->unique();

                $absen = Absen::where('id_booking', $kode_booking)->first();

                return view('peminjaman.showPinjam', [
                    'booking' => $booking,
                    'booking_items' => $bookingItems,
                    'ruangan' => $booking['ruangans'][0] ?? null, // Menampilkan data ruangan pertama
                    'database_items' => $booking['database_items'],
                    'tools' => $tools, // Menambahkan informasi tools
                    'absen' => $absen, // Data absen (name dan signature)

                ]);
            } else {
                // Jika booking tidak ditemukan
                return redirect()->route('peminjaman.index')->with('error', 'Booking tidak ditemukan.');
            }
        } else {
            report('Error accessing API: ' . $response->status());
            return redirect()->route('peminjaman.index')->with('error', 'Gagal mengambil data dari API.');
        }
    }
}
