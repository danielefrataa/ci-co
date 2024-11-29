<?php
namespace App\Http\Controllers;

use App\Models\Absen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\PeminjamanBarang;
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
    
        // Inisialisasi variabel untuk API
        $today = Carbon::now()->toDateString(); // Format: 2024-11-19
        Carbon::setLocale('id');
        $allBookings = collect();
        $page = 1;
        $maxPages = 2; // Batasi maksimal 2 halaman untuk mencegah infinite loop
    
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
    
        // Cari booking berdasarkan kode_booking
        $bookingData = $allBookings->first(function ($item) use ($id_booking) {
            return strtolower($item['booking_code']) === $id_booking;
        });
    
        if (!$bookingData) {
            Log::warning('Booking tidak ditemukan untuk kode: ' . $id_booking);
            return redirect()->route('inputkode.show')->with('gagal', 'Booking tidak ditemukan.');
        }
    
        Log::info('Data Booking ditemukan: ', $bookingData);
    
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

    // Pengecekan data peminjaman barang di database
    $hasPeminjamanDatabase = PeminjamanBarang::where('kode_booking', $kode_booking)->exists();

    // Pengecekan data peminjaman barang di API
    $apiUrl = "https://event.mcc.or.id/api/event?status=booked&booking_code={$kode_booking}";
    $response = Http::withHeaders([
        'X-API-KEY' => 'JUrrUHAAdBepnJjpfVL2nY6mx9x4Cful4AhYxgs3Qj6HEgryn77KOoDr6BQZgHU1', // Ganti dengan API Key Anda
    ])->withoutVerifying()->get($apiUrl);

    $hasPeminjamanApi = false;

    if ($response->successful()) {
        $data = $response->json();
        $booking = collect($data['data'] ?? [])->firstWhere('booking_code', $kode_booking);

        if ($booking) {
            $tools = $booking['tools'] ?? null; // Pastikan tools ada
            $hasPeminjamanApi = !empty($tools); // Jika tools tidak kosong, berarti ada peminjaman
        }
    } else {
        Log::error('Gagal mengakses API untuk kode: ' . $kode_booking);
    }

    // Jika ada peminjaman barang di database atau API
    if ($hasPeminjamanDatabase || $hasPeminjamanApi) {
        Log::info('Peminjaman barang ditemukan untuk kode: ' . $kode_booking);
        return redirect()->route('peminjaman.show', $kode_booking)
                         ->with('success', 'Check-in berhasil. Anda memiliki peminjaman barang.');
    }

    // Jika tidak ada peminjaman barang
    Log::info('Tidak ada peminjaman barang untuk kode: ' . $kode_booking);
    return redirect()->route('inputkode.show')->with('success', 'Check-in berhasil. Terima kasih!');
}

    
}