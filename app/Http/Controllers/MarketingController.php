<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\PeminjamanBarang;
use Carbon\Carbon;
use App\Models\booking;

class MarketingController extends Controller
{
    //Bisa nggak
    private $apiKey = 'JUrrUHAAdBepnJjpfVL2nY6mx9x4Cful4AhYxgs3Qj6HEgryn77KOoDr6BQZgHU1';

    public function index(Request $request)
{
    $today = Carbon::now()->toDateString(); // Format: 2024-11-19
    $allBookings = collect();
    $page = 1;
    $maxPages = 10; // Batasi maksimal 10 halaman untuk mencegah infinite loop

    $searchTerm = strtolower($request->get('search', ''));

    do {
        $url = "https://event.mcc.or.id/api/event?status=booked&date={$today}&page={$page}";
        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
        ])->withoutVerifying()->get($url);

        if ($response->successful()) {
            $data = collect($response->json()['data'] ?? []);
            $allBookings = $allBookings->merge($data);
            $page++;
        } else {
            report('Error accessing API: ' . $response->status());
            break;
        }
    } while ($data->isNotEmpty() && $page <= $maxPages);

    $filteredBookings = $allBookings->map(function ($item) use ($today) {
        $bookingItems = collect($item['booking_items'] ?? [])
            ->filter(fn($bookingItem) => $bookingItem['booking_date'] === $today)
            ->values();

        // Hitung waktu mulai dan selesai
        $startTime = $bookingItems->min('booking_hour');
        $endTime = $bookingItems->max('booking_hour');

        $item['start_time'] = $startTime ? Carbon::createFromTime($startTime, 0)->format('H:i') : null;
        $item['end_time'] = $endTime ? Carbon::createFromTime($endTime, 0)->format('H:i') : null;

        $ruanganIds = $bookingItems->pluck('ruangan_id')->unique();
        $item['ruangans'] = collect($item['ruangans'] ?? [])
            ->filter(fn($ruangan) => $ruanganIds->contains($ruangan['id']))
            ->values()
            ->toArray();

        $item['booking_items'] = $bookingItems->toArray();
        // Ambil data dari database berdasarkan kode_booking
        $item['database_items'] = PeminjamanBarang::where('kode_booking', $item['booking_code'])->get();

        return $item;
    })->filter(function ($item) use ($today, $searchTerm) {
        if (empty($item['booking_items'])) {
            return false;
        }

        if ($searchTerm) {
            $eventName = strtolower($item['name'] ?? '');
            $picName = strtolower($item['pic_name'] ?? '');

            return strpos($eventName, $searchTerm) !== false || strpos($picName, $searchTerm) !== false;
        }

        return true;
    });
 // Tambahkan data database hanya untuk booking_code yang ada di database
 $filteredBookings->each(function ($item) {
    // Menambahkan items dari database berdasarkan booking_code
    $item['database_items'] = PeminjamanBarang::where('kode_booking', $item['booking_code'])->get();
});
    // Sorting dan pagination
    $filteredBookings = $filteredBookings->sortBy(function ($item) {
        $floor = $item['ruangans'][0]['floor'] ?? '0';
        $startTime = $item['start_time'] ?? '00:00';
        return [$floor, $startTime];
    });

    $currentPage = (int) $request->get('page', 1);
    $perPage = (int) $request->get('per_page', 6);
    $paginatedBookings = $filteredBookings->forPage($currentPage, $perPage);

    return view('marketing.peminjaman', [
        'bookings' => $paginatedBookings,
        'totalPages' => ceil($filteredBookings->count() / $perPage),
        'currentPage' => $currentPage,
        'perPage' => $perPage,
    ]);
}


    public function store(Request $request)
    {
        // Ambil kode booking dari form (no need to check the database)
        $kode_booking = $request->input('kode_booking');

        // Menyimpan beberapa item barang
        foreach ($request->input('items') as $item) {
            $newItem = new PeminjamanBarang;
            $newItem->nama_item = $item['nama_item'];
            $newItem->jumlah = $item['jumlah'];
            $newItem->kode_booking = $kode_booking; // Set kode_booking
            $newItem->save();
        }

        return redirect()->route('marketing.peminjaman')->with('success', 'Booking berhasil ditambahkan.');
    }
  
}