<?
use Illuminate\Database\Seeder;
use App\Models\Booking;

class BookingSeeder extends Seeder
{
    public function run()
    {
        Booking::create([
            'booking_code' => 'MCC-BK0012',
            'event_name' => 'Global Visionary Conference: Project Discussion',
            'room_time' => 'Ruang Kelas Lantai 3, 19:00 - 21:00',
            'user_name' => 'Zevanna Zennata',
            'status' => 'check-in',
        ]);

        Booking::create([
            'booking_code' => 'MCC-BK0089',
            'event_name' => 'Latihan Vokal Paduan Suara',
            'room_time' => 'Studio Musik Lantai 4, 16:00 - 20:00',
            'user_name' => 'Doni Ramadhan',
            'status' => 'booked',
        ]);

        Booking::create([
            'booking_code' => 'MCC-BK0072',
            'event_name' => 'Apa itu Programming',
            'room_time' => 'Lab Komputer Lantai 4, 12:00 - 17:00',
            'user_name' => 'Lukman Hakim',
            'status' => 'check-out',
        ]);
    }
}
