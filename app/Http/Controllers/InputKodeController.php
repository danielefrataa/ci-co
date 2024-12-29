<?php
namespace App\Http\Controllers;

use App\Models\Absen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\PeminjamanBarang;
use Illuminate\Support\Facades\Auth;

class InputKodeController extends Controller
{
    private $apiKey = 'JUrrUHAAdBepnJjpfVL2nY6mx9x4Cful4AhYxgs3Qj6HEgryn77KOoDr6BQZgHU1';

    // Tampilkan halaman input kode
    public function show()
    {
        return view('front_office.inputkode');
    }
    public function validateRole()
    {
        $user = Auth::user();
    
        if ($user && $user->role === 'frontoffice') {
            // Redirect ke dashboard Front Office jika role adalah frontoffice
            return redirect()->route('front_office.dashboard');
        }
    
        // Redirect ke dashboard utama jika role bukan frontoffice
        return redirect()->route('dashboard');
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
    

    // Pengecekan apakah kode booking sudah check-in
    $checkIn = \App\Models\Absen::where('id_booking', $id_booking)->first(); // Sesuaikan dengan model dan kolom yang digunakan untuk check-in
    if ($checkIn) {
        $today = Carbon::today()->format('Y-m-d'); // Mendapatkan tanggal hari ini dalam format Y-m-d
    
        // Periksa apakah sudah ada check-in untuk tanggal hari ini berdasarkan tabel absen
        $existingCheckIn = $checkIn->where('tanggal', $today)->first();
    
        if ($existingCheckIn) {
            Log::info('Booking dengan kode ' . $id_booking . ' sudah check-in pada tanggal ' . $today);
            return redirect()->route('inputkode.show')->with('gagal', 'Anda sudah melakukan check-in dengan kode booking ini untuk hari ini.');
        }
    }
    
    
    // Jika tidak ada check-in untuk hari ini, lanjutkan proses check-in
    

    // Inisialisasi variabel untuk API
    $today = Carbon::now()->toDateString(); // Format: 2024-11-19
    Carbon::setLocale('id');
    $allBookings = collect();
    $page = 1;
    $maxPages = rand(2, 5); // Atur secara acak antara 3 hingga 5 halaman

    // Iterasi API untuk mengambil semua data
    do {
        Log::info("Mengakses halaman: {$page}");
        Log::info('Tanggal yang digunakan untuk API: ' . $today);
        Log::info('Waktu sekarang: ' . Carbon::now()->toDateTimeString());

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
        return redirect()->route('inputkode.show')->with('gagal', 'Kode ini sudah dipesan atau tidak ditemukan.');
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
        return redirect()->route('inputkode.show')->with('gagal', 'Anda sudah melakukan check-in dengan kode booking ini.');
    }
}

    // Proses simpan data check-in setelah melengkapi form


    public function completeCheckIn(Request $request, $kode_booking)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'signatureData' => 'required|string',
        ]);

        $signatureData = $request->input('signatureData');

        if (empty($signatureData)) {
            return back()->with('error', 'Tanda tangan tidak ditemukan.');
        }

        $today = Carbon::now()->toDateString();

        // Periksa apakah check-in sudah dilakukan untuk kombinasi kode_booking dan tanggal
        $cek = Absen::where([
            'id_booking' => $kode_booking,
            'tanggal' => $today,
        ])->first();

        if ($cek) {
            Log::warning("Check-in sudah dilakukan untuk kode: {$kode_booking} pada tanggal: {$today}");
            return redirect()->route('inputkode.show')->with('gagal', 'Anda sudah check-in hari ini.');
        }

        // Fetch booking data dari API
        $apiUrl = "https://event.mcc.or.id/api/event?status=booked&booking_code={$kode_booking}";
        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
        ])->withoutVerifying()->get($apiUrl);

        if (!$response->successful()) {
            Log::error("Gagal mengakses API untuk kode: {$kode_booking}");
            return back()->with('error', 'Gagal mengakses API. Silakan coba lagi.');
        }

        $bookingData = $response->json();
        $booking = collect($bookingData['data'] ?? [])->firstWhere('booking_code', $kode_booking);

        if (!$booking) {
            Log::error("Booking tidak ditemukan untuk kode: {$kode_booking}");
            return back()->with('error', 'Booking tidak ditemukan.');
        }

        $validRuanganIds = collect($booking['booking_items'] ?? [])
        ->filter(function ($item) use ($today) {
            return isset($item['booking_date'], $item['ruangan_id']) && $item['booking_date'] === $today;
        })
        ->pluck('ruangan_id') // Hanya ambil ruangan_id
        ->unique(); // Pastikan tidak ada duplikasi
    // 2. Loop melalui ruangans dan cocokkan dengan validRuanganIds
    foreach ($booking['ruangans'] as $ruangan) {
        if ($validRuanganIds->contains($ruangan['id'])) {
            // 3. Jika cocok, insert ke database
            Absen::create([
                'id_booking' => $kode_booking, // ID booking
                'tanggal' => $today,           // Tanggal hari ini
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'signature' => $signatureData,
                'status' => 'Check-in',
                'ruangan' => $ruangan['name'], // Nama ruangan
                'lantai' => $ruangan['floor'], // Lantai ruangan
            ]);
        }
    }

        Log::info("Check-in berhasil untuk kode: {$kode_booking} pada tanggal: {$today}");

        // Cek apakah ada peminjaman barang
        $hasPeminjamanDatabase = PeminjamanBarang::where('kode_booking', $kode_booking)->exists();
        $hasPeminjamanApi = !empty($booking['tools'] ?? null);

        if ($hasPeminjamanDatabase || $hasPeminjamanApi) {
            Log::info("Peminjaman barang ditemukan untuk kode: {$kode_booking}");
            return redirect()->route('peminjaman.show', $kode_booking)
                ->with('success', 'Check-in berhasil. Anda memiliki peminjaman barang.');
        }

        Log::info("Tidak ada peminjaman barang untuk kode: {$kode_booking}");

        // Redirect berdasarkan role pengguna
        // if (auth()->user()->role === 'frontoffice') {
        //     return redirect()->route('front_office.dashboard')
        //         ->with('success', 'Check-in berhasil. Terima kasih!');
        // }

        // Jika bukan FO, redirect ke halaman input kode
        return redirect()->route('inputkode.show')->with('success', 'Check-in berhasil. Terima kasih!');
    }

    public function checkout(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode_booking' => 'required|string',
        ]);

        $kodeBooking = strtolower(trim($request->kode_booking));

        // Ambil data absen terbaru berdasarkan kode_booking
        $absenTerbaru = Absen::where('id_booking', $kodeBooking)
            ->orderBy('tanggal', 'desc') // Urutkan berdasarkan tanggal terbaru
            ->first();

        if (!$absenTerbaru) {
            return redirect()->back()->with('gagal', 'Data booking tidak ditemukan.');
        }
        // Update status menjadi Check-out jika absen ditemukan
        try {
            $absenTerbaru->update(['status' => 'Check-out']);
            return redirect()->back()->with('sukses', 'Check-out berhasil dilakukan.');
        } catch (\Exception $e) {
            Log::error("Error saat check-out: {$e->getMessage()}");
            return redirect()->back()->with('gagal', 'Terjadi kesalahan saat mengubah status.');
        }
    }
}