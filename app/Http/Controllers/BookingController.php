<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function showDetails($kode_booking)
    {
        // Retrieve the booking by 'kode_booking'
        $booking = Booking::where('kode_booking', $kode_booking)->firstOrFail();

        // Return the view with the booking details
        return view('booking.details', compact('booking'));
    }
}
