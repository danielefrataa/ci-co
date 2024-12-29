<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\list_barang;
use App\Models\PeminjamanBarang;

class ProductionController extends Controller
{
    private $apiKey = 'JUrrUHAAdBepnJjpfVL2nY6mx9x4Cful4AhYxgs3Qj6HEgryn77KOoDr6BQZgHU1';

    public function index(Request $request)
    {
        // Get the date from the request or default to today
        $filterDate = Carbon::parse($request->get('date', Carbon::now()->toDateString()));

        // Get list of barang
        $listBarang = list_barang::all();

        $allBookings = collect();
        
        // Define $searchTerm here, using the request parameter
        $searchTerm = strtolower($request->get('search', ''));

        $page = 1;
        $maxPages = 5; // Limit the number of pages per date

        // Fetch data from the API, paginated
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

        return view('production.index', [
            'bookings' => $paginatedBookings,
            'listBarang' => $listBarang,
            'totalPages' => ceil($filteredBookings->count() / $perPage),
            'currentPage' => $currentPage,
            'perPage' => $perPage,
            'filterDate' => $filterDate->toDateString(),
        ]);
    }
}
