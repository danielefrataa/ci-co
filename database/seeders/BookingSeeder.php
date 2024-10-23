<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking; // pastikan model Booking sudah ada

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $booking = [
            [
                'kode_booking' => 'BK-001',
                'nama' => 'BEM UB',
            ],
            [
                'kode_booking' => 'BK-002',
                'nama' => 'BEM UM',
            ],
        ];

        // Insert data ke tabel bookings
        Booking::insert($booking);
    }
}
