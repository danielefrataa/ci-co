<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\PeminjamanBarang;
use App\Models\Room;
class BookingsController extends Controller
{
    public function showDetails($kode_booking)
    {
        // Retrieve the booking by 'kode_booking'
        $booking = Booking::where('kode_booking', $kode_booking)->firstOrFail();
        $borrowedItems = PeminjamanBarang::where('kode_booking', $kode_booking)->get();
        // Return the view with the booking details
        return view('booking.details', compact('booking', 'borrowedItems'));
    }
    // Display the bookings list
    public function index(Request $request)
{
    // Get the filter and search parameters from the request
    $status = $request->get('status');
    $search = $request->get('search');
    $perPage = $request->get('per_page', 6); // Default ke 6 jika tidak ada parameter per_page

    // Query bookings with related absen status
    $query = Booking::whereHas('absen', function($query) {
        $query = Booking::whereHas('absen', function($query) {
            $query->whereIn('status', ['approved', 'checkin', 'checkout']);
        });
            });

       // Filter berdasarkan status
       if ($status) {
        $query->whereHas('absen', function($query) use ($status) {
            $query->where('status', $status);
        });
    }

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('nama_event', 'LIKE', "%$search%")
              ->orWhere('nama_pic', 'LIKE', "%$search%")
              ->orWhere('nama_organisasi', 'LIKE', "%$search%");
        });
    }
    

    $bookings = $query->orderBy('kode_booking', 'asc')->paginate(6);
    foreach ($bookings as $booking) {
        // Cari lantai berdasarkan nama ruangan di tabel Ruangan
        $ruangan = Room::where('nama_ruangan', $booking->nama_ruangan)->first();
        $booking->lantai = $ruangan ? $ruangan->lantai : null; // Simpan lantai ke booking
    }
    return view('front_office.dashboard', compact('bookings'));
}


    // Update the booking status
    public function updateStatus(Request $request)
    {
        $booking = Booking::find($request->id);
        if ($booking && $booking->absen->isNotEmpty()) {
            // Mengupdate status absen
            $absen = $booking->absen->last();
            $absen->status = $request->status;
            $absen->save();
    
            return response()->json(['success' => true, 'message' => 'Status successfully updated']);
        }
    
        return response()->json(['success' => false, 'message' => 'Booking or Absen record not found']);
    }
    


}
