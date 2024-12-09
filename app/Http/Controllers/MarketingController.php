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
        // Get the date from the request or default to today
        $filterDate = Carbon::parse($request->get('date', Carbon::now()->toDateString()));

        $allBookings = collect();
        $searchTerm = strtolower($request->get('search', ''));

        $page = 1;
        $maxPages = 5; // Limit the number of pages per date

        do {
            $url = "https://event.mcc.or.id/api/event?status=booked&date={$filterDate->toDateString()}&page={$page}";
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
            ])->withoutVerifying()->get($url);

            if ($response->successful()) {
                $data = collect($response->json()['data'] ?? []);
                $allBookings = $allBookings->merge($data);
                $page++;
            } else {
                report("Error accessing API for date {$filterDate->toDateString()}: " . $response->status());
                break;
            }
        } while ($data->isNotEmpty() && $page <= $maxPages);

        // Process and filter the data
        $filteredBookings = $allBookings->map(function ($item) {
            $bookingItems = collect($item['booking_items'] ?? []);

            // Calculate start and end times
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

            // Fetch related database items based on booking code
            $item['database_items'] = PeminjamanBarang::where('kode_booking', $item['booking_code'])->get();

            return $item;
        })->filter(function ($item) use ($searchTerm) {
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

        // Sort by start time
        $filteredBookings = $filteredBookings->sortBy([
            ['start_time', 'asc']
        ]);

        // Pagination
        $currentPage = (int) $request->get('page', 1);
        $perPage = (int) $request->get('per_page', 6);
        $paginatedBookings = $filteredBookings->forPage($currentPage, $perPage);

        return view('marketing.peminjaman', [
            'bookings' => $paginatedBookings,
            'totalPages' => ceil($filteredBookings->count() / $perPage),
            'currentPage' => $currentPage,
            'perPage' => $perPage,
            'filterDate' => $filterDate->toDateString(),
        ]);
    }


    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'kode_booking' => 'nullable|string', // kode_booking boleh null
            'items' => 'nullable|array',        // items boleh null, tapi jika ada harus berupa array
            'items.*.nama_item' => 'required_with:items|string', // Validasi untuk setiap item
            'items.*.jumlah' => 'required_with:items|integer|min:1',
        ]);

        // Ambil kode booking dari form (jika ada, gunakan; jika tidak, simpan sebagai null)
        $kode_booking = $validated['kode_booking'] ?? null;

        // Pastikan items selalu array
        $items = $validated['items'] ?? [];

        // Menyimpan beberapa item barang
        foreach ($items as $item) {
            PeminjamanBarang::create([
                'nama_item' => $item['nama_item'],
                'jumlah' => $item['jumlah'],
                'kode_booking' => $kode_booking, // Set kode_booking (null jika tidak ada)
            ]);
        }

        return redirect()->route('marketing.peminjaman')->with('success', 'Booking berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        try {
            $item = PeminjamanBarang::findOrFail($id);
            $item->delete();

            return response()->json(['success' => true, 'message' => 'Item berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus item'], 500);
        }
    }
}
