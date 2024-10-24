<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use Illuminate\Http\Request;

class InputKodeController extends Controller
{
    public function show()
    {
        return view('front_office.inputkode');
    }
    //
    public function match(Request $request)
    {
        $request->validate([
            'kode_booking' => 'required|string', // Assuming this is the kode_booking from the scan
        ]);


        $booking = Absen::where('kode_booking', $request->kode_booking)->first();

        return redirect()->route('booking.details', ['kode_booking' => $booking->kode_booking]) // Use kode_booking instead of id
            ->with('success', 'Silahkan lengkapi data Anda.');
    }
}
