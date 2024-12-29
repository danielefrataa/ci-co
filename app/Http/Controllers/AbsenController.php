<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen;
use App\Models\PeminjamanBarang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class AbsenController extends Controller
{
    private $apiKey = 'JUrrUHAAdBepnJjpfVL2nY6mx9x4Cful4AhYxgs3Qj6HEgryn77KOoDr6BQZgHU1';

    // Proses input kode booking
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_booking' => 'required|string',
        ]);
    
        $id_booking = strtolower(trim($request->id_booking));
        Log::info('Kode Booking Diterima: ' . $id_booking);
    
        // Inisialisasi variabel untuk API
        $today = Carbon::now()->toDateString(); // Format: 2024-11-19
        Carbon::setLocale('id');
        $allBookings = collect();
        $page = 1;
        $maxPages = rand(3, 5); // Atur secara acak antara 3 hingga 5 halaman
    
        // Iterasi API untuk mengambil semua data
        do {
            Log::info("Mengakses halaman: {$page}");
        
            $url = "https://event.mcc.or.id/api/event?status=booked&date={$today}&page={$page}";
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
            ])->withoutVerifying()->get($url);
        
            if ($response->successful()) {
                $data = collect($response->json()['data'] ?? []);
                $allBookings = $allBookings->merge($data);
        
                // Periksa apakah kode booking ditemukan
                $bookingData = $data->first(function ($item) use ($id_booking) {
                    return strtolower($item['booking_code']) === $id_booking;
                });
        
                if ($bookingData) {
                    Log::info('Data Booking ditemukan pada halaman: ' . $page);
                    break; // Hentikan iterasi jika data ditemukan
                }
        
                $page++;
            } else {
                Log::error("Gagal mengambil data dari halaman: {$page} dengan status: {$response->status()}");
                Log::error('Error accessing API: ' . $response->body());
        
                // Jika respons 400 dengan pesan "data not found", anggap data habis
                if ($response->status() === 400 && str_contains($response->body(), '"data not found"')) {
                    break; // Hentikan iterasi jika data tidak ditemukan
                }
        
                // Jika error lainnya, kembalikan error
                return redirect()->route('inputkode.show')->with('gagal', 'Gagal mengambil data dari API.');
            }
        } while ($page <= $maxPages);
        
    
        // Cari booking berdasarkan kode_booking
        $bookingData = $allBookings->first(function ($item) use ($id_booking) {
            return strtolower($item['booking_code']) === $id_booking;
        });
    
        if (!$bookingData) {
            Log::warning('Booking tidak ditemukan untuk kode: ' . $id_booking);
            return redirect()->route('dashboard')->with('gagal', 'Booking tidak ditemukan.');
        }
    
        Log::info('Data Booking ditemukan: ', $bookingData);
    
        // Cek apakah booking sudah check-in
        $checkin = \App\Models\Absen::where('id_booking', $id_booking)->first();
    
        if ($checkin) {
            // Jika sudah check-in, tampilkan pesan dan berhenti
            return redirect()->route('dashboard')->with('gagal', 'Anda sudah melakukan check-in dengan kode booking ini.');
        }
    
        // Format tanggal dan hari dari API
        $apiBookingDate = Carbon::parse($bookingData['date'] ?? '');
        $formattedDate = $apiBookingDate->toDateString();  // Format tanggal YYYY-MM-DD
        $dayOfWeek = $apiBookingDate->isoFormat('dddd');  // Nama hari dalam minggu (misalnya: Senin, Selasa, dll)
    
        // Ambil data booking_items yang terkait dengan booking_id dan tanggal yang sesuai
        $bookingItems = collect($bookingData['booking_items'] ?? [])
            ->filter(function ($item) use ($formattedDate) {
                return isset($item['booking_date']) && $item['booking_date'] === $formattedDate;
            });
    
        if ($bookingItems->isEmpty()) {
            Log::warning('Tidak ada booking_items untuk kode: ' . $id_booking);
            return redirect()->route('inputkode.show')->with('gagal', 'Tidak ada item booking untuk tanggal ini.');
        }
    
        Log::info('Data Booking Items ditemukan: ', $bookingItems->toArray());
    
        // Pemrosesan detail booking
        try {
            $ruangan = collect($bookingData['ruangans'] ?? [])->first();
    
            if (!$ruangan) {
                return redirect()->route('inputkode.show')->with('gagal', 'Data ruangan tidak ditemukan.');
            }
    
            $startTime = $bookingItems->min('booking_hour');
            $endTime = $bookingItems->max('booking_hour');
    
            $roomDetails = [
                'room_name' => $ruangan['name'] ?? 'Tidak Diketahui',
                'room_floor' => $ruangan['floor'] ?? 'Tidak Diketahui',
                'room_description' => $ruangan['description'] ?? '',
                'room_facility' => $ruangan['facility'] ?? '',
            ];
    
            // Tampilkan halaman booking.details
            return view('booking.details', [
                'booking' => $bookingData,
                'roomDetails' => $roomDetails,
                'bookingItems' => $bookingItems,
                'formattedDate' => $formattedDate,
                'dayOfWeek' => $dayOfWeek,
                'startTime' => $startTime ? Carbon::createFromTime($startTime)->format('H:i') : null,
                'endTime' => $endTime ? Carbon::createFromTime($endTime)->format('H:i') : null,
            ]);
        } catch (\Exception $e) {
            Log::error('Kesalahan saat memproses data booking: ' . $e->getMessage());
            return redirect()->route('inputkode.show')->with('gagal', 'Terjadi kesalahan saat memproses data booking.');
        }
    }
    
    // Fungsi check-in dengan form
    public function checkinstore(Request $request)
    {
        // Validasi input form check-in
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'signatureData' => 'required',
        ]);

        // Ambil kode_booking dari sesi
        $kode_booking = $request->session()->get('kode_booking');
        // Pastikan booking ada di API
        $today = Carbon::now()->toDateString();
        $allBookings = collect();
        $page = 1;
        $maxPages = 5; // Batasi maksimal 2 halaman untuk mencegah infinite loop

        // Iterasi API untuk mengambil semua data
        do {
            Log::info("Mengakses halaman: {$page}");

            $url = "https://event.mcc.or.id/api/event?status=booked&date={$today}&page={$page}";
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
            ])->withoutVerifying()->get($url);

            if ($response->successful()) {
                $data = collect($response->json()['data'] ?? []);
                $allBookings = $allBookings->merge($data);
                $page++;
            } else {
                Log::error("Gagal mengambil data dari halaman: {$page} dengan status: {$response->status()}");
                Log::error('Error accessing API: ' . $response->body());  // Log respons lengkap untuk debugging
                return redirect()->route('inputkode.show')->with('gagal', 'Gagal mengambil data dari API.');
            }

        } while ($data->isNotEmpty() && $page <= $maxPages);

        $bookingData = $allBookings->first(function ($item) use ($kode_booking) {
            return strtolower($item['booking_code']) === $kode_booking;
        });

        if (!$bookingData) {
            return redirect('/')->with('gagal', 'Booking tidak ditemukan.');
        }
        $bookingData = $response->json();
        $booking = collect($bookingData['data'] ?? [])->firstWhere('booking_code', $kode_booking);

        if (!$booking) {
            Log::error("Booking tidak ditemukan untuk kode: {$kode_booking}");
            return back()->with('error', 'Booking tidak ditemukan.');
        }

        // Simpan atau update data check-in
        $absen = Absen::where('id_booking', $kode_booking)->first();

        if (!$absen) {
            Absen::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'signature' => $request->signature,
                'id_booking' => $request->id_booking,
                'tanggal' => $request->tanggal,
                'duty_officer_id' => $request->duty_officer_id, // Ensure this is being passed
                'status' => $request->status,
                'ruangan' => $booking['ruangans'][0]['name'], // Jika ada ruangan terkait
                'lantai' => $booking['ruangans'][0]['floor'], // Jika ada ruangan terkait

            ]);
        } else {
            $absen->update([
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'signature' => $request->input('signatureData'),
                'status' => 'Check-in',
            ]);
        }

        // Cek apakah ada peminjaman barang
        $peminjaman = PeminjamanBarang::where('kode_booking', $kode_booking)->first();

        if ($peminjaman) {
            return redirect()->route('peminjaman.show', $peminjaman->kode_booking);
        } else {
            return redirect()->route('dashboard')->with('success', 'Check-in berhasil tanpa peminjaman barang.');
        }
    }
}
