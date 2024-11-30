<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class RoomListController extends Controller
{
    private $apiKey = 'JUrrUHAAdBepnJjpfVL2nY6mx9x4Cful4AhYxgs3Qj6HEgryn77KOoDr6BQZgHU1';

    public function getAllApiData()
    {
        $allData = collect();
        $page = 1;

        do {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
            ])->withoutVerifying()->get("https://event.mcc.or.id/api/ruangan?page={$page}");

            if ($response->successful()) {
                $data = collect($response->json()['data'] ?? []);
                $allData = $allData->merge($data);
                $page++;
            } else {
                break;
            }
        } while ($data->isNotEmpty());

        return collect($allData);
    }

    public function index()
    {
        // Fetch all data from API
        $apiRooms = $this->getAllApiData();

        // Fetch booking times grouped by room
        $timeRanges = $this->getBookingTimes();

        // Fetch status from database
        $dbStatuses = Room::all()->pluck('status', 'nama_ruangan');

        // Combine API data with database status and booking times
        $rooms = $apiRooms->map(function ($room) use ($dbStatuses, $timeRanges) {
            $bookingTime = $timeRanges[$room['name']] ?? ['start_time' => null, 'end_time' => null];
            return [
                'name' => $room['name'],
                'floor' => $room['floor'],
                'status' => $dbStatuses[$room['name']] ?? 'Unknown',
                'start_time' => $bookingTime['start_time'],
                'end_time' => $bookingTime['end_time'],
            ];
        });

        // Pass data to view
        return view('front_office.roomList', compact('rooms'));
    }

    public function filter(Request $request)
    {
        // Fetch all data from API
        $apiRooms = $this->getAllApiData();

        // Remove duplicates based on name and floor
        $apiRooms = $apiRooms->unique(function ($room) {
            return $room['name'] . $room['floor'];
        });

        // Fetch booking times grouped by room
        $timeRanges = $this->getBookingTimes();

        // Fetch status from database
        $dbStatuses = Room::all()->pluck('status', 'nama_ruangan');

        // Check if search filter has been applied
        $isSearchApplied = $request->has('search') && $request->search != '';

        // Apply search filter
        if ($isSearchApplied) {
            $search = $request->search;

            $apiRooms = $apiRooms->filter(function ($room) use ($search) {
                return stripos($room['name'], $search) !== false || stripos($room['floor'], $search) !== false;
            });
        }

        // Apply lantai (floor) filter
        if ($request->has('lantai') && $request->lantai != '') {
            $lantai = $request->lantai;

            $apiRooms = $apiRooms->filter(function ($room) use ($lantai) {
                return stripos($room['floor'], $lantai) !== false;
            });
        }

        // Apply status filter
        if ($request->has('status') && $request->status != '') {
            $statusFilter = $request->status;

            $apiRooms = $apiRooms->filter(function ($room) use ($dbStatuses, $statusFilter) {
                $status = $dbStatuses[$room['name']] ?? 'Unknown';
                return stripos($status, $statusFilter) !== false;
            });
        }

        // Combine API data with database status and booking times
        $rooms = $apiRooms->map(function ($room) use ($dbStatuses, $timeRanges) {
            $bookingTime = $timeRanges[$room['name']] ?? ['start_time' => null, 'end_time' => null];
            return [
                'name' => $room['name'],
                'floor' => $room['floor'],
                'status' => $dbStatuses[$room['name']] ?? 'Unknown',
                'start_time' => $bookingTime['start_time'],
                'end_time' => $bookingTime['end_time'],
            ];
        });

        // If search is applied, reset page to 1
        $currentPage = $isSearchApplied ? 1 : $request->get('page', 1);

        // Pagination logic
        $perPage = $request->get('per_page', 9); // Items per page from query or default to 9
        $totalPages = ceil($rooms->count() / $perPage); // Total number of pages
        $paginatedRooms = $rooms->forPage($currentPage, $perPage); // Paginate rooms

        // Pass data to view
        return view('front_office.roomList', [
            'rooms' => $paginatedRooms,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
            'lantai' => $request->lantai,
            'status' => $request->status,
        ]);
    }

    private function getBookingTimes()
    {
        $today = Carbon::now()->toDateString(); // Today's date
        $allBookings = collect();
        $page = 1;
        $maxPages = 10;

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

        // Map booking times to rooms
        return $allBookings->flatMap(function ($item) {
            $bookingItems = collect($item['booking_items'] ?? []);
            $ruangans = collect($item['ruangans'] ?? []);

            return $ruangans->mapWithKeys(function ($ruangan) use ($bookingItems) {
                $ruanganId = $ruangan['id'];
                $ruanganName = $ruangan['name'];

                // Filter bookings for this room
                $roomBookings = $bookingItems->where('ruangan_id', $ruanganId);

                $startTime = $roomBookings->min('booking_hour');
                $endTime = $roomBookings->max('booking_hour');

                return [
                    $ruanganName => [
                        'start_time' => $startTime !== null ? Carbon::createFromTime($startTime, 0)->format('H:i') : null,
                        'end_time' => $endTime !== null ? Carbon::createFromTime($endTime, 0)->format('H:i') : null,
                    ],
                ];
            });
        });
    }
}
