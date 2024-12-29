<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\PeminjamanBarang;
use App\Models\list_barang;
use Carbon\Carbon;

class MarketingController extends Controller
{
    private $apiKey = 'JUrrUHAAdBepnJjpfVL2nY6mx9x4Cful4AhYxgs3Qj6HEgryn77KOoDr6BQZgHU1';

    public function index(Request $request)
    {
        // Get the date from the request or default to today
        $filterDate = Carbon::parse($request->get('date', Carbon::now()->toDateString()));

        // get data from list_barang
        $listBarang = list_barang::all();

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
        $filteredBookings = $allBookings->map(function ($item) use ($filterDate) {
            $bookingItems = collect($item['booking_items'] ?? []);

            // Filter booking items to only include items with a matching booking_date
            $matchingBookingItems = $bookingItems->filter(function ($bookingItem) use ($filterDate) {
                return $bookingItem['booking_date'] == $filterDate->toDateString();
            });

            // If no booking items match the filtered date, return null
            if ($matchingBookingItems->isEmpty()) {
                return null;
            }

            // Calculate start and end times
            $startTime = $matchingBookingItems->min('booking_hour');
            $endTime = $matchingBookingItems->max('booking_hour');

            $item['start_time'] = $startTime ? Carbon::createFromTime($startTime, 0)->format('H:i') : null;
            $item['end_time'] = $endTime ? Carbon::createFromTime($endTime, 0)->format('H:i') : null;

            // Extract ruangan IDs from the filtered booking items
            $ruanganIds = $matchingBookingItems->pluck('ruangan_id')->unique();

            // Filter ruangans to only include those matching the IDs
            $item['ruangans'] = collect($item['ruangans'] ?? [])
                ->filter(fn($ruangan) => $ruanganIds->contains($ruangan['id']))
                ->values()
                ->toArray();

            $item['booking_items'] = $matchingBookingItems->toArray();

            // Fetch related database items based on booking code
            $item['database_items'] = PeminjamanBarang::where('kode_booking', $item['booking_code'])->get();

            return $item;
        })->filter(function ($item) use ($searchTerm) {
            if (empty($item['booking_items'])) {
                return false;
            }

            if ($searchTerm) {
                // Convert the entire $item to a single string to search all fields
                $itemText = strtolower(json_encode($item));

                return strpos($itemText, $searchTerm) !== false;
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
            'listBarang' => $listBarang,
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
                'created_by' => auth()->id(), // Menyimpan ID user yang menambahkan barang

            ]);
        }

        return redirect()->route('marketing.peminjaman')->with('success', 'Booking berhasil ditambahkan.');
    }

    public function destroy($id)
{
    try {
        $item = PeminjamanBarang::findOrFail($id);
        $item->deleted_by = auth()->id(); // Menyimpan ID user yang menghapus
        $item->save(); // Simpan perubahan sebelum soft delete
        $item->delete(); // Soft delete

        return response()->json(['success' => true, 'message' => 'Item berhasil dihapus']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Gagal menghapus item'], 500);
    }
}
public function history(Request $request)
{
    // Ambil data peminjaman barang beserta informasi siapa yang menambahkan dan menghapus
    $data = PeminjamanBarang::withTrashed()
        ->with(['createdBy', 'deletedBy'])
        ->paginate(10);

    // Mengirim data ke view beserta informasi pagination
    return view('marketing.history', [
        'data' => $data,
        'currentPage' => $data->currentPage(),
        'totalPages' => $data->lastPage(),
        'perPage' => $data->perPage(),
    ]);
}



}
