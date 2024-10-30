<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    // Display the bookings list
    public function index(Request $request)
    {
        // Get the filter and search parameters from the request
        $status = $request->get('status');
        $search = $request->get('search');

        // Query bookings based on filters
        $query = Booking::query();

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where('nama_event', 'LIKE', "%$search%")
                  ->orWhere('kode_booking', 'LIKE', "%$search%")
                  ->orWhere('user_name', 'LIKE', "%$search%");
        }

        $bookings = $query->get();

        return view('front_office.dashboard', compact('bookings'));
    }
}
