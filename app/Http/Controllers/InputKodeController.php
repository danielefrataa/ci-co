<?php
namespace App\Http\Controllers;

use App\Models\Absen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class InputKodeController extends Controller
{
    private $apiKey = 'JUrrUHAAdBepnJjpfVL2nY6mx9x4Cful4AhYxgs3Qj6HEgryn77KOoDr6BQZgHU1';

    // Tampilkan halaman input kode
    public function show()
    {
        return view('front_office.inputkode');
    }

    // Proses input kode booking
    public function match(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_booking' => 'required|string',
        ]);
        
        $id_booking = strtolower(trim($request->id_booking));
        Log::info('Kode Booking Diterima: ' . $id_booking);
        
        // Ambil data dari API
        $today = Carbon::now()->toDateString();  // Tanggal hari ini
        $apiUrl = "https://event.mcc.or.id/api/event?status=booked&date={$today}";
        
        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
        ])->withoutVerifying()->get($apiUrl);
        
        // Jika API gagal diakses
        if ($response->failed()) {
            Log::error('Gagal mengambil data dari API: ', $response->json());
            return redirect()->route('inputkode.show')->with('gagal', 'Gagal mengambil data booking dari API.');
        }
        
        // Ambil data booking berdasarkan kode_booking
        $bookingData = collect($response->json()['data'])
            ->first(function ($item) use ($id_booking) {
                return strtolower($item['booking_code']) === $id_booking;
            });
    
        if (!$bookingData) {
            Log::warning('Booking tidak ditemukan untuk kode: ' . $id_booking);
            return redirect()->route('inputkode.show')->with('gagal', 'Booking tidak ditemukan.');
        }
    
        Log::info('Data Booking ditemukan: ', $bookingData);
    
        // Ambil tanggal yang diterima dari API
        $apiBookingDate = Carbon::parse($bookingData['date'] ?? '');  // Mengambil tanggal dari API dan mengonversinya ke format Carbon
    
        if (!$apiBookingDate) {
            Log::warning('Tanggal booking tidak ditemukan untuk kode: ' . $id_booking);
            return redirect()->route('inputkode.show')->with('gagal', 'Tanggal booking tidak valid.');
        }
    
        // Set locale ke Bahasa Indonesia
        $apiBookingDate->locale('id'); // Mengubah locale ke Bahasa Indonesia
    
        // Format tanggal dan hari dari API
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
    
        // Ambil jam terkecil (start_time) dan jam terbesar (end_time) berdasarkan booking_hour
        $startTime = $bookingItems->min('booking_hour');  // Jam terkecil
        $endTime = $bookingItems->max('booking_hour');    // Jam terbesar
    
        // Format waktu
        $startTimeFormatted = Carbon::createFromTime($startTime, 0)->format('H:i');  // Format jam mulai
        $endTimeFormatted = Carbon::createFromTime($endTime, 0)->format('H:i');      // Format jam selesai
    
        // Tentukan data untuk ditampilkan
        $room = collect($bookingData['ruangans'] ?? [])->first();
        $roomDetails = [
            'room_name' => $room['name'] ?? 'Tidak Diketahui',
            'room_floor' => $room['floor'] ?? 'Tidak Diketahui',
            'room_description' => $room['description'] ?? '',
            'room_facility' => $room['facility'] ?? '',
        ];
    
        // Tampilkan halaman booking.details untuk melengkapi data
        return view('booking.details', [
            'booking' => $bookingData,
            'roomDetails' => $roomDetails,
            'startTime' => $startTimeFormatted,  // Kirimkan waktu mulai
            'endTime' => $endTimeFormatted,      // Kirimkan waktu selesai
            'formattedDate' => $formattedDate,  // Tanggal dari API
            'dayOfWeek' => $dayOfWeek,          // Hari dalam minggu dari API
        ]);
    }
    
    // Proses simpan data check-in setelah melengkapi form
    

    public function completeCheckIn(Request $request, $kode_booking)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'signatureData' => 'required|string', // Pastikan signatureData diterima
        ]);
    
        // Ambil data tanda tangan (signatureData)
        $signatureData = $request->input('signatureData');
    
        // Cek jika data signatureData kosong atau tidak valid
        if (empty($signatureData)) {
            return back()->with('error', 'Tanda tangan tidak ditemukan.');
        }
    
        // Cek apakah sudah check-in pada tanggal yang sama
        $today = Carbon::now()->toDateString();
        $cek = Absen::where([
            'id_booking' => $kode_booking,
            'tanggal' => $today,
        ])->first();
    
        if ($cek) {
            Log::warning('Check-in sudah dilakukan untuk kode: ' . $kode_booking);
            return redirect()->route('inputkode.show')->with('gagal', 'Anda Sudah Check-in');
        }
    
        // Simpan data check-in ke tabel Absen dengan signature dalam bentuk base64
        Absen::create([
            'id_booking' => $kode_booking,
            'tanggal' => $today,
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'signature' => $signatureData, // Simpan langsung base64 signature di database
            'status' => 'Check-in',
        ]);
    
        Log::info('Check-in berhasil untuk kode: ' . $kode_booking);
    
        return redirect()->route('inputkode.show')->with('success', 'Check-in berhasil. Terima kasih!');
    }

    
}
