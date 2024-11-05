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
            $query->where(function($q) use ($search) {
                $q->where('nama_event', 'LIKE', "%$search%")
                  ->orWhere('kode_booking', 'LIKE', "%$search%")
                  ->orWhere('user_name', 'LIKE', "%$search%");
            });
        }

        $bookings = $query->get();
        $bookings = $query->orderBy('kode_booking', 'asc')->paginate(6);


        return view('front_office.dashboard', compact('bookings'));
    }

    // Update the booking status
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::find($id);
        
        if ($booking) {
            $booking->status = $request->status;
            $booking->save();

            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Booking not found.'], 404);
    }
}

