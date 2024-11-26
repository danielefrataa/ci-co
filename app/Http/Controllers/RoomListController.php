<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

        // Fetch status from database
        $dbStatuses = Room::all()->pluck('status', 'nama_ruangan');

        // Combine API data with database status
        $rooms = $apiRooms->map(function ($room) use ($dbStatuses) {
            return [
                'name' => $room['name'],
                'floor' => $room['floor'],
                'status' => $dbStatuses[$room['name']] ?? 'Unknown',
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

        // Combine API data with database status
        $rooms = $apiRooms->map(function ($room) use ($dbStatuses) {
            return [
                'name' => $room['name'],
                'floor' => $room['floor'],
                'status' => $dbStatuses[$room['name']] ?? 'Unknown',
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
    public function getHour() {
        
    }
}
