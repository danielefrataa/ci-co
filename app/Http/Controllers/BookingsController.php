<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\Booking;

class BookingsController extends Controller
{
    private $apiKey = 'JUrrUHAAdBepnJjpfVL2nY6mx9x4Cful4AhYxgs3Qj6HEgryn77KOoDr6BQZgHU1';

    // Fungsi untuk mengambil data dari API
    public function index()
    {
        $url = "https://event.mcc.or.id/api/event";
        $params = [
            'limit' => 20,
            'status' => 'booked',
            'created_at' => '2024-11-17T18:17:32.000000Z',
            'created_at' => '2024-11-17'
            
        ];

        // Permintaan GET dengan API Key
        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
        ])->withoutVerifying()->get($url, $params);

        // Cek apakah response berhasil
        if ($response->successful()) {
            $bookings = $response->json(); // Ambil data API
            return view('front_office.dashboard', compact('bookings'));
        }

        // Jika gagal, tampilkan pesan error
        return view('errors.generic', ['error' => 'Tidak dapat mengambil data dari API.']);
    }

    // Fungsi untuk mengambil data dari database
    // public function index()
    // {
    //     // Ambil data bookings dari database
    //     $bookings = Booking::with(['ruangans', 'absen']) // Relasi yang diperlukan
    //         ->orderBy('created_at', 'desc') // Sorting berdasarkan tanggal terbaru
    //         ->paginate(10); // 10 data per halaman

    //     // Kirim ke view
    //     return view('bookings.index', compact('bookings'));
    // }

    // Fungsi untuk rentang jam berdasarkan dua waktu
    // public function convertToTimeRange($startHour, $endHour)
    // {
    //     $startTime = Carbon::createFromFormat('H', $startHour)->format('H:i');
    //     $endTime = Carbon::createFromFormat('H', $endHour)->format('H:i');
    //     return $startTime . ' - ' . $endTime;
    // }
}
