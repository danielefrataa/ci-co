<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\DutyOfficer;

class BookingsController extends Controller
{
    private $apiKey = 'JUrrUHAAdBepnJjpfVL2nY6mx9x4Cful4AhYxgs3Qj6HEgryn77KOoDr6BQZgHU1';

    public function index()
    {
        // Ambil data dari tabel duty_officer
        $duty_officer = DutyOfficer::all();

        // URL dan parameter API
        $url = "https://event.mcc.or.id/api/event";
        $params = [
            'limit' => 20,
            'status' => 'booked',
            'created_at' => '2024-11-17'
        ];

        // Permintaan ke API
        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
        ])->withoutVerifying()->get($url, $params);

        // Periksa apakah API berhasil
        if ($response->successful()) {
            $bookings = $response->json(); // Ambil data API
        } else {
            $bookings = null; // Tetapkan null jika gagal
        }

        // Kirim data ke view
        return view('front_office.dashboard', compact('duty_officer', 'bookings'));
    }
}
