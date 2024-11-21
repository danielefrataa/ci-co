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

        // Fetch status from database
        $dbStatuses = Room::all()->pluck('status', 'nama_ruangan');

        // Apply search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            $apiRooms = $apiRooms->filter(function ($room) use ($search) {
                return stripos($room['name'], $search) !== false || stripos($room['floor'], $search) !== false;
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

        // Pagination logic
        $currentPage = $request->get('page', 1); // Current page from query or default to 1
        $perPage = $request->get('per_page', 9); // Items per page from query or default to 9
        $totalPages = ceil($rooms->count() / $perPage); // Total number of pages
        $paginatedRooms = $rooms->forPage($currentPage, $perPage); // Paginate rooms

        // Pass data to view
        return view('front_office.roomList', [
            'rooms' => $paginatedRooms,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
        ]);
    }
}
