<?php

namespace App\Http\Controllers;

use App\Models\Absen; // Ensure the Absen model is imported
use App\Models\Booking; // Ensure the Booking model is imported
use Illuminate\Http\Request;

class InputKodeController extends Controller
{
    public function show()
    {
        return view('front_office.inputkode');
    }

    public function match(Request $request)
    {
        $request->validate([
            'id_booking' => 'required|string', // Assuming this is the kode_booking from the scan
        ]);

        // Check if the user has already checked in today
        $cek = Absen::where([
            'id_booking' => $request->id_booking, // This should be the kode_booking
            'tanggal' => date('Y-m-d')
        ])->first(); // Use first() instead of row()

        if ($cek) {
            return redirect('/front-office/inputkode')->with('gagal', 'Anda Sudah Check-in');
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
