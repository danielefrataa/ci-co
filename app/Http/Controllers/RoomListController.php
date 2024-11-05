<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomListController extends Controller
{
    //
    public function index(Request $request)
    {
        $statusMapping = [
            'kosong' => 'Check-out',
            'sedang digunakan' => 'Check-in',
            'dipesan' => 'Booked',
        ];

        $query = Room::query();

        // Apply floor filter if no search query is present
        if (!$request->has('search') && $request->has('lantai') && $request->lantai != '') {
            $query->where('lantai', $request->lantai);
        }

        // Handle the search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            $query->where(function ($q) use ($search, $statusMapping) {
                $q->where('nama_ruangan', 'LIKE', "%$search%")
                    ->orWhere('lantai', 'LIKE', "%$search%");

                foreach ($statusMapping as $userTerm => $dbTerm) {
                    if (stripos($userTerm, $search) !== false) {
                        $q->orWhere('status', 'LIKE', "%$dbTerm%");
                    }
                }
            });
        }

        // Perform the pagination
        $rooms = $query->latest()->paginate(9);

        // Return the view with the filtered and combined data
        return view('front_office.roomList', compact('rooms'));
    }
}
