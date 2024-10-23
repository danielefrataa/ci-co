<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen; // Ensure the Absen model is imported
use App\Models\Booking; // Ensure the Booking model is imported

class AbsenController extends Controller
{
    public function store(Request $request)
    {
        // Validate input data
        $request->validate([
            'id_booking' => 'required|string', // Assuming this is the kode_booking from the scan
        ]);

        // Check if the user has already checked in today
        $cek = Absen::where([
            'id_booking' => $request->id_booking, // This should be the kode_booking
            'tanggal' => date('Y-m-d')
        ])->first(); // Use first() instead of row()

        if ($cek) {
            return redirect('/')->with('gagal', 'Anda Sudah Check-in');
        }

        // Create a new check-in record
        Absen::create([
            'id_booking' => $request->id_booking,
            'tanggal' => date('Y-m-d'),
        ]);

        // Get the booking details for the provided kode_booking
        $booking = Booking::where('kode_booking', $request->id_booking)->first();

        // Redirect to the booking details page
        return redirect()->route('booking.details', ['kode_booking' => $booking->kode_booking]) // Use kode_booking instead of id
            ->with('success', 'Silahkan lengkapi data Anda.');
    }
}
