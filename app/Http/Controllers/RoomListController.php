<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class RoomListController extends Controller
{
    private $apiKey = 'JUrrUHAAdBepnJjpfVL2nY6mx9x4Cful4AhYxgs3Qj6HEgryn77KOoDr6BQZgHU1';

    private function fetchApiData($apiType)
    {
        $allData = collect();
        $page = 1;

        do {
            if ($apiType === 'rooms') {
                $url = "https://event.mcc.or.id/api/ruangan?page={$page}";
            } elseif ($apiType === 'bookings') {
                $today = Carbon::now()->toDateString();
                $url = "https://event.mcc.or.id/api/event?status=booked&date={$today}&page={$page}";
            } else {
            }

            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
            ])->withoutVerifying()->get($url);

            if ($response->successful()) {
                $data = collect($response->json('data') ?? []);
                $allData = $allData->merge($data);
                $page++;
            } else {
                report("Error accessing {$apiType} API: " . $response->status());
                break;
            }
        } while ($data->isNotEmpty());

        return $allData;
    }

    public function index($request)
    {
        $allRooms = $this->fetchApiData('rooms');
        $timeRanges = $this->getBookingTimes();

        // Database statuses, keyed by room
        $dbStatuses = Room::query()
            ->select('ruangan', 'lantai', 'status')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->unique(function ($room) {
                return strtolower(trim("{$room->ruangan}|{$room->lantai}"));
            })
            ->mapWithKeys(function ($room) {
                return [strtolower(trim("{$room->ruangan}|{$room->lantai}")) => $room->status];
            });

        // Initialize processed counts
        $rooms = $allRooms->map(function ($room) use ($timeRanges, $dbStatuses) {
            $roomId = $room['id'];
            $cacheKey = "processed_count_{$roomId}";

            // Get the current count from the cache
            $currentCount = Cache::get($cacheKey, 0);

            $key = strtolower(trim("{$room['name']}|{$room['floor']}"));
            $status = $dbStatuses->get($key, 'unknown');
            // Get all bookings for this room
            $allBookings = $timeRanges
                ->where('ruangan_id', $roomId)
                ->sortBy('start_time')
                ->values();
            if ($status === 'Check-out') {
                Cache::put($cacheKey, ++$currentCount);
                if ($currentCount < $allBookings->count()) {
                    $nextBooking = $allBookings->get($currentCount);
                    if ($nextBooking) {
                        return [
                            'name' => $room['name'],
                            'floor' => $room['floor'],
                            'status' => 'unknown',
                            'start' => $nextBooking['start_time'],
                            'end' => $nextBooking['end_time'],
                            'booking_code' => $nextBooking['booking_code'] ?? null,
                        ];
                    }
                } else {
                    return [
                        'name' => $room['name'],
                        'floor' => $room['floor'],
                        'status' => $status,
                        'start' => null,
                        'end' => null,
                        'booking_code' => null,
                    ];
                }
            } else {
                // For Check-in or unknown, get the current booking
                $currentBooking = $allBookings->get($currentCount);
                if ($currentBooking) {
                    return [
                        'name' => $room['name'],
                        'floor' => $room['floor'],
                        'status' => $status,
                        'start' => $currentBooking['start_time'],
                        'end' => $currentBooking['end_time'],
                        'booking_code' => $currentBooking['booking_code'] ?? null,
                    ];
                    
                } else {
                    return [
                        'name' => $room['name'],
                        'floor' => $room['floor'],
                        'status' => $status,
                        'start' => null,
                        'end' => null,
                        'booking_code' => null,
                    ];
                }
            }
        });

        return view('front_office.roomList', compact('rooms'));
    }

    public function filter(Request $request)
    {
        // Fetch data from the API
        $apiRooms = $this->fetchApiData('rooms');
        $timeRanges = $this->getBookingTimes();

        // Fetch room statuses from the database
        $dbStatuses = Room::query()
            ->select('ruangan', 'lantai', 'status')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->unique(function ($room) {
                return strtolower(trim("{$room->ruangan}|{$room->lantai}"));
            })
            ->mapWithKeys(function ($room) {
                return [strtolower(trim("{$room->ruangan}|{$room->lantai}")) => $room->status];
            });

        // Apply filters
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $apiRooms = $apiRooms->filter(function ($room) use ($search) {
                return stripos(strtolower($room['name']), $search) !== false || stripos(strtolower($room['floor']), $search) !== false;
            });
        }

        if ($request->filled('lantai')) {
            $lantai = strtolower($request->lantai);
            $apiRooms = $apiRooms->filter(function ($room) use ($lantai) {
                return strtolower($room['floor']) === $lantai;
            });
        }
        // Reset counts if the day has changed
        $rooms = $apiRooms->map(function ($room) use ($timeRanges, $dbStatuses) {
            $roomId = $room['id'];
            $cacheKey = "processed_count_{$roomId}";

            // Get the current count from the cache
            $currentCount = Cache::get($cacheKey, 0);

            $key = strtolower(trim("{$room['name']}|{$room['floor']}"));
            $status = $dbStatuses->get($key, 'unknown');

            // Get all bookings for this room
            $allBookings = $timeRanges
                ->where('ruangan_id', $roomId)
                ->sortBy('start_time')
                ->values();

            if ($status === 'Check-out') {
                Cache::put($cacheKey, ++$currentCount);
                if ($currentCount < $allBookings->count()) {
                    $nextBooking = $allBookings->get($currentCount);
                    if ($nextBooking) {
                        return [
                            'name' => $room['name'],
                            'floor' => $room['floor'],
                            'status' => 'unknown',
                            'start' => $nextBooking['start_time'],
                            'end' => $nextBooking['end_time'],
                            'booking_code' => $nextBooking['booking_code'] ?? null,
                        ];
                    }
                } else {
                    return [
                        'name' => $room['name'],
                        'floor' => $room['floor'],
                        'status' => $status,
                        'start' => null,
                        'end' => null,
                        'booking_code' => null,
                    ];
                }
            } else {
                // For Check-in or unknown, get the current booking
                $currentBooking = $allBookings->get($currentCount);
                if ($currentBooking) {
                    return [
                        'name' => $room['name'],
                        'floor' => $room['floor'],
                        'status' => $status,
                        'start' => $currentBooking['start_time'],
                        'end' => $currentBooking['end_time'],
                        'booking_code' => $currentBooking['booking_code'] ?? null,
                    ];
                    
                } else {
                    return [
                        'name' => $room['name'],
                        'floor' => $room['floor'],
                        'status' => $status,
                        'start' => null,
                        'end' => null,
                        'booking_code' => null,
                    ];
                }
            }
        });


        if ($request->filled('status')) {
            $statusFilter = strtolower($request->status);
            $rooms = $rooms->filter(function ($room) use ($statusFilter) {

                $status = strtolower($room['status']);
                return $status === $statusFilter;
            });
        }
        // Pagination
        $perPage = $request->get('per_page', 9);
        $currentPage = $request->get('page', 1);
        $paginatedRooms = $rooms->forPage($currentPage, $perPage);

        // Return the view with paginated results
        return view('front_office.roomList', [
            'rooms' => $paginatedRooms,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
            'totalPages' => ceil($rooms->count() / $perPage),
            'lantai' => $request->lantai,
            'status' => $request->status,
        ]);
    }



    private function getBookingTimes()
    {
        $allBookings = $this->fetchApiData('bookings');

        return $allBookings->flatMap(function ($item) {
            // Filter booking items by today's date
            $today = Carbon::now()->toDateString();

            $bookingItems = collect($item['booking_items'] ?? [])
                ->filter(fn($bookingItem) => $bookingItem['booking_date'] === $today)
                ->sortBy('booking_hour');

            $ruangans = collect($item['ruangans'] ?? []);

            // Map and flip the keys to make ruangan_id the key
            return $ruangans->mapWithKeys(function ($ruangan) use ($bookingItems, $item) {
                $ruanganId = $ruangan['id'];

                // Calculate the start and end times
                $startTime = $bookingItems->where('ruangan_id', $ruanganId)->min('booking_hour');
                $endTime = $bookingItems->where('ruangan_id', $ruanganId)->max('booking_hour');

                // Add booking_code only if booking_items exist
                $bookingCode = $bookingItems->isNotEmpty() ? $item['booking_code'] ?? null : null;

                return [
                    $ruanganId => [
                        'ruangan_id' => $ruanganId,
                        'start_time' => $startTime !== null ? Carbon::createFromTime($startTime)->format('H:i') : null,
                        'end_time' => $endTime !== null ? Carbon::createFromTime($endTime)->format('H:i') : null,
                        'booking_code' => $bookingCode,
                    ],
                ];
            });
        });
    }
}
