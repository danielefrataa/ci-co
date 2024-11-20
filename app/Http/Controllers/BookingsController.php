<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookingsController extends Controller
{
    private $apiKey = 'JUrrUHAAdBepnJjpfVL2nY6mx9x4Cful4AhYxgs3Qj6HEgryn77KOoDr6BQZgHU1';

    /**
     * Menampilkan daftar booking untuk hari ini
     */
    public function index(Request $request)
    {
        $today = Carbon::now()->toDateString(); // Format: 2024-11-19
        $allBookings = collect();
        $page = 1;
        $maxPages = 10; // Batasi maksimal 10 halaman untuk mencegah infinite loop

        // Ambil nilai 'search' dari input pencarian dan validasi
        $searchTerm = strtolower($request->get('search', ''));

        // Loop untuk mengambil semua halaman data dari API
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
                // Log error untuk debugging jika API gagal
                report('Error accessing API: ' . $response->status());
                break;
            }

        } while ($data->isNotEmpty() && $page <= $maxPages);

        // Filter data booking untuk hari ini
        $filteredBookings = $allBookings->filter(function ($item) use ($today, $searchTerm) {
            if (!isset($item['booking_items'])) return false;

            foreach ($item['booking_items'] as $bookingItem) {
                if (isset($bookingItem['booking_date']) && $bookingItem['booking_date'] === $today) {
                    // Jika ada searchTerm, cari berdasarkan event name atau kode booking
                    if ($searchTerm) {
                        $eventName = strtolower($item['name'] ?? ''); // Nama event
                        $kodeBooking = strtolower($item['pic_name'] ?? ''); // Kode booking

                        if (strpos($eventName, $searchTerm) !== false || strpos($kodeBooking, $searchTerm) !== false) {
                            return true; // Temukan hasil yang sesuai
                        }
                    } else {
                        return true; // Tampilkan semua data jika tidak ada pencarian
                    }
                }
            }
            return false;
        });

        // Pagination manual
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
        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
        ])->withoutVerifying()->get($apiUrl);

        if ($response->successful()) {
            $data = collect($response->json()['data']);
            $booking = $data->firstWhere('booking_code', $kode_booking);

            if ($booking) {
                $bookingItems = collect($booking['booking_items'] ?? []);

                // Ambil ruangan dari key 'ruangans'
                $room = collect($booking['ruangans'] ?? [])->first();

                if (!$room) {
                    return redirect()->route('front_office.dashboard')->with('error', 'Data ruangan tidak ditemukan.');
                }

                $roomDetails = [
                    'room_name' => $room['name'] ?? 'Tidak Diketahui',
                    'room_floor' => $room['floor'] ?? 'Tidak Diketahui',
                    'room_description' => $room['description'] ?? '',
                    'room_facility' => $room['facility'] ?? '',
                ];

                return view('booking.details', compact('booking', 'bookingItems', 'roomDetails'));
            }

            return redirect()->route('front_office.dashboard')->with('error', 'Data booking tidak ditemukan.');
        }

        return view('errors.generic', [
            'error' => 'Tidak dapat mengambil data dari API. Silakan coba lagi nanti.',
        ]);
    }
}
