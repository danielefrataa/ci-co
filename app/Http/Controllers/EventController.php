<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class EventController extends Controller
{
    private $apiKey = 'JUrrUHAAdBepnJjpfVL2nY6mx9x4Cful4AhYxgs3Qj6HEgryn77KOoDr6BQZgHU1';

    public function index()
    {
        $url = "https://event.mcc.or.id/api/event";
        $params = [
            'limit' => 300,
            'status' => 'booked',
            'created_at[gte]' => '2024-06-10', // Tanggal mulai
            'created_at[lte]' => '2024-06-11', // Tanggal selesai
        ];

        // Permintaan GET dengan API Key
        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
        ])->withoutVerifying()->get($url, $params);

        // Cek apakah response berhasil
        if ($response->successful()) {
            $events = $response->json();
            return view('events.index', compact('events'));
        } 

        // Menangani jika response gagal
        return view('errors.generic', ['error' => 'Tidak dapat mengambil data dari API.']);
    }
}