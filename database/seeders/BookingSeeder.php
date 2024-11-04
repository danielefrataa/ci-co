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
                'id' => 2,
                'kode_booking' => 'BK-002',
                'nama_event' => 'Seminar Bisnis',
                'ruangan' => 'Aula Utama',
                'waktu_mulai' => '10:00',
                'waktu_selesai' => '12:00',
                'user_name' => 'Ayu Setiawati',
                'status' => 'Booked',
            ],
            [
                'id' => 3,
                'kode_booking' => 'BK-003',
                'nama_event' => 'Workshop Fotografi',
                'ruangan' => 'Ruangan Lantai 3',
                'waktu_mulai' => '13:00',
                'waktu_selesai' => '15:00',
                'user_name' => 'Budi Prasetyo',
                'status' => 'Check-out',
            ],
            [
                'id' => 4,
                'kode_booking' => 'BK-004',
                'nama_event' => 'Pelatihan Coding',
                'ruangan' => 'Laboratorium Komputer',
                'waktu_mulai' => '09:00',
                'waktu_selesai' => '17:00',
                'user_name' => 'Siti Nurhaliza',
                'status' => 'Booked',
            ],
            [
                'id' => 5,
                'kode_booking' => 'BK-005',
                'nama_event' => 'Presentasi Startup',
                'ruangan' => 'Ruangan Lantai 5',
                'waktu_mulai' => '14:00',
                'waktu_selesai' => '16:00',
                'user_name' => 'Rizki Saputra',
                'status' => 'Check-in',
            ],
            [
                'id' => 6,
                'kode_booking' => 'BK-006',
                'nama_event' => 'Diskusi Publik',
                'ruangan' => 'Aula Utama',
                'waktu_mulai' => '08:00',
                'waktu_selesai' => '11:00',
                'user_name' => 'Fajar Hidayat',
                'status' => 'Check-out',
            ],
            [
                'id' => 7,
                'kode_booking' => 'BK-007',
                'nama_event' => 'Kursus Desain Grafis',
                'ruangan' => 'Ruangan Lantai 2',
                'waktu_mulai' => '13:00',
                'waktu_selesai' => '15:00',
                'user_name' => 'Rina Melati',
                'status' => 'Booked',
            ],
            [
                'id' => 8,
                'kode_booking' => 'BK-008',
                'nama_event' => 'Rapat Koordinasi',
                'ruangan' => 'Ruangan Rapat',
                'waktu_mulai' => '09:00',
                'waktu_selesai' => '11:30',
                'user_name' => 'Andi Wijaya',
                'status' => 'Check-in',
            ],
            [
                'id' => 9,
                'kode_booking' => 'BK-009',
                'nama_event' => 'Pelatihan Kewirausahaan',
                'ruangan' => 'Aula Utama',
                'waktu_mulai' => '14:00',
                'waktu_selesai' => '17:00',
                'user_name' => 'Indah Lestari',
                'status' => 'Booked',
            ],
            [
                'id' => 10,
                'kode_booking' => 'BK-010',
                'nama_event' => 'Seminar Teknologi',
                'ruangan' => 'Ruangan Lantai 3',
                'waktu_mulai' => '10:00',
                'waktu_selesai' => '12:00',
                'user_name' => 'Dedi Santoso',
                'status' => 'Check-out',
            ],
            [
                'id' => 11,
                'kode_booking' => 'BK-011',
                'nama_event' => 'Workshop Kuliner',
                'ruangan' => 'Ruangan Lantai 1',
                'waktu_mulai' => '08:00',
                'waktu_selesai' => '10:00',
                'user_name' => 'Maya Pertiwi',
                'status' => 'Check-in',
            ],
           
        ];

        // Insert data ke tabel bookings
        Booking::insert($booking);
    }
}
