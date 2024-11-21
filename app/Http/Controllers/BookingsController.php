<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\DutyOfficer;

class BookingsController extends Controller
{
    private $apiKey = 'JUrrUHAAdBepnJjpfVL2nY6mx9x4Cful4AhYxgs3Qj6HEgryn77KOoDr6BQZgHU1';

<<<<<<< HEAD
    public function index()
    {
        // Ambil data dari tabel duty_officer
        // $duty_officer = DutyOfficer::all();

        // URL dan parameter API
        $url = "https://event.mcc.or.id/api/event";
        $params = [
            'limit' => 20,
            'status' => 'booked',
            'created_at' => '2024-11-17'
        ];

        // Permintaan ke API
=======
    /**
     * Menampilkan daftar booking untuk hari ini
     */
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
    $filteredBookings = $filteredBookings->sortBy(function ($item) {
        // Urutkan berdasarkan lantai terlebih dahulu (ascending)
        $floor = $item['ruangans'][0]['floor'] ?? '0'; // Ambil lantai dari ruangan pertama
        $startTime = $item['start_time'] ?? '00:00'; // Jika start_time kosong, beri nilai default
    
        return [$floor, $startTime];
    });
    $currentPage = (int) $request->get('page', 1);
    $perPage = (int) $request->get('per_page', 6);
    $paginatedBookings = $filteredBookings->forPage($currentPage, $perPage);

    return view('front_office.dashboard', [
        'bookings' => $paginatedBookings,
        'totalPages' => ceil($filteredBookings->count() / $perPage),
        'currentPage' => $currentPage,
        'perPage' => $perPage,
    ]);
}


    /**
     * Menampilkan detail booking berdasarkan kode_booking
     */
    public function showDetails($kode_booking)
    {
        if (!$kode_booking) {
            return redirect()->route('front_office.dashboard')->with('error', 'Kode booking tidak valid.');
        }

        $apiUrl = "https://event.mcc.or.id/api/event";
>>>>>>> 8aa96ad08bfa49a3ea9a72104ce56d4d934465ca
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
        return view('front_office.dashboard', compact('bookings'));
    }
}
