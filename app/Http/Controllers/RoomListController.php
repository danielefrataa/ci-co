<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomListController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->input('search'); // Get search term
        $lantai = $request->input('lantai'); // Get floor filter

        // Start the query
        $rooms = Room::query();

        // Apply the search filter across all fields
        if ($search) {
            $rooms->where(function ($query) use ($search) {
                $query->where('nama_ruangan', 'LIKE', "%$search%")
                    ->orWhere('status', 'LIKE', "%$search%")
                    ->orWhere('lantai', 'LIKE', "%$search%"); // Include lantai in the search
            });
        }

        // Apply the floor filter only if it's selected
        if ($lantai) {
            $rooms->where('lantai', $lantai);
        }

        // Get the results, ordered by the latest and paginate
        $rooms = $rooms->latest()->paginate(9);

        // Return the view with the filtered and combined data
        return view('front_office.roomList', compact('rooms'));
    }
}
