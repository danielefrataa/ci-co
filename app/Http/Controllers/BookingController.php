<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PeminjamanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookingController extends Controller
{
    public function showDetails($kode_booking)
    {
        // Retrieve the booking by 'kode_booking'
        $booking = Booking::where('kode_booking', $kode_booking)->firstOrFail();
        $borrowedItems = PeminjamanBarang::where('kode_booking', $kode_booking)->get();
        // Return the view with the booking details
        return view('booking.details', compact('booking', 'borrowedItems'));
    }

    public function index(Request $request)
{
    $status = $request->input('status');
    $query = Booking::query();

    if ($status) {
        $query->where('status', $status);
    }

    $bookings = $query->get();
    return view('bookings.index', compact('bookings'));
}

    public function getBookingData()
    {
        $response = Http::withHeaders([
            'x-api-key' => 'JUrrUHAAdBepnJjpfVL2nY6mx9x4Cful4AhYxgs3Qj6HEgryn77KOoDr6BQZgHU1'
        ])->get('https://event.mcc.or.id/api/event?limit=1&status=booked');
        
        $data = $response->json();
    
        return view('booking.index', compact('data'));
    }
    
    
    

}
