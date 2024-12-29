<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\DutyOfficer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Absen;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BookingsExport;
use App\Models\booking;
use Illuminate\Support\Facades\Log;
class BookingsController extends Controller
{
    private $apiKey = 'JUrrUHAAdBepnJjpfVL2nY6mx9x4Cful4AhYxgs3Qj6HEgryn77KOoDr6BQZgHU1';
    
    public function index(Request $request)
    {
        $today = Carbon::now()->toDateString();
        $allBookings = collect();
        $page = 1;
        $maxPages = 10;

        // Ambil nilai status filter dari request
        $statusFilter = $request->get('status', ''); // Ambil nilai status dari request
        $searchTerm = strtolower($request->get('search', ''));
        $dutyOfficers = DutyOfficer::all();
        
        do {
            $url = "https://event.mcc.or.id/api/event?status=booked&date={$today}&page={$page}";
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
            ])->withoutVerifying()->get($url);

            if ($response->successful()) {
                $data = collect($response->json()['data'] ?? []);
                $allBookings = $allBookings->merge($data);
                $page++;
            } else {
                report('Error accessing API: ' . $response->status());
                break;
            }
        } while ($data->isNotEmpty() && $page <= $maxPages);

        $filteredBookings = $allBookings->map(function ($item) use ($today) {
            $bookingItems = collect($item['booking_items'] ?? [])
                ->filter(fn($bookingItem) => $bookingItem['booking_date'] === $today)
                ->values();

            $startTime = $bookingItems->min('booking_hour');
            $endTime = $bookingItems->max('booking_hour');

            $item['start_time'] = $startTime ? Carbon::createFromTime($startTime, 0)->format('H:i') : null;
            $item['end_time'] = $endTime ? Carbon::createFromTime($endTime, 0)->format('H:i') : null;

            $ruanganIds = $bookingItems->pluck('ruangan_id')->unique();
            $item['ruangans'] = collect($item['ruangans'] ?? [])
                ->filter(fn($ruangan) => $ruanganIds->contains($ruangan['id']))
                ->values()
                ->toArray();

            $item['booking_items'] = $bookingItems->toArray();

            // Tambahkan data absen terbaru berdasarkan tanggal hari ini
            $absenData = Absen::where('id_booking', $item['booking_code'])
                ->whereDate('tanggal', $today) // Pastikan hanya tanggal hari ini
                ->latest('updated_at') // Ambil data terbaru jika ada perubahan
                ->first();

            if ($absenData) {
                $item['absen'] = [
                    'name' => $absenData->name,
                    'status' => $absenData->status,
                    'duty_officer' => $absenData->duty_officer,
                    'phone' => $absenData->phone,
                ];
            }

            return $item;
        })->filter(function ($item) use ($today, $searchTerm, $statusFilter) {
            if (empty($item['booking_items'])) {
                return false;
            }

             if ($searchTerm) {
                // Convert the entire $item to a single string to search all fields
                $itemText = strtolower(json_encode($item));

                return strpos($itemText, $searchTerm) !== false;
            }
            if ($statusFilter) {
                $currentStatus = $item['absen']['status'] ?? 'Booked'; // Default jika tidak ada data absen
                return $currentStatus === $statusFilter;
            }

            return true;
        });

        // Sort by floor and start time
        $filteredBookings = $filteredBookings->sortBy(function ($item) {
            $floor = $item['ruangans'][0]['floor'] ?? '0';
            $startTime = $item['start_time'] ?? '00:00';
            return [$floor, $startTime];
        });

        // Get the current page and per page
        $currentPage = (int) $request->get('page', 1);
        $perPage = (int) $request->get('per_page', 6);

        // Paginate the bookings
        $paginatedBookings = $filteredBookings->forPage($currentPage, $perPage);

    return view('front_office.dashboard', [
        'bookings' => $paginatedBookings,
        'totalPages' => ceil($filteredBookings->count() / $perPage),
        'currentPage' => $currentPage,
        'perPage' => $perPage,
        'dutyOfficers' => $dutyOfficers,
    ]);
}
public function showBookingList()
{
    $bookings = Booking::with('dutyOfficer')->get();
    
    $bookings->transform(function($booking) {
        $booking->dutyOfficerIsFilled = !is_null($booking->duty_officer);
        return $booking;
    });

    return view('dashboard', ['bookings' => $bookings]);
}

public function exportBookings(Request $request)
    {
        $filters = $request->all(); // Ambil semua parameter filter
        $format = $request->input('format', 'csv'); // Format default adalah CSV

        $filename = "bookings.$format";
        Log::info('Format: ' . $format);

        // Eksekusi berdasarkan format
        switch ($format) {
            case 'pdf':
                return Excel::download(new BookingsExport($filters), $filename, \Maatwebsite\Excel\Excel::DOMPDF);

            case 'csv':
                return Excel::download(new BookingsExport($filters), $filename, \Maatwebsite\Excel\Excel::CSV);

            case 'xlsx':
                return Excel::download(new BookingsExport($filters), $filename, \Maatwebsite\Excel\Excel::XLSX);

            default:
                abort(400, 'Format tidak didukung.');
        }
    }


public function updateDutyOfficer(Request $request)
{
    $validated = $request->validate([
        'booking_id' => 'required|string',
        'duty_officer_id' => 'required|exists:duty_officers,id',
    ]);

        $booking = Absen::where('id_booking', $validated['booking_id'])->first();

        if ($booking) {
            $dutyOfficer = DutyOfficer::find($validated['duty_officer_id']);
            $booking->duty_officer_id = $dutyOfficer->id;
            $booking->save();

            return response()->json([
                'success' => true,
                'officer_name' => $dutyOfficer->nama_do,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Booking tidak ditemukan.',
        ], 404);
    }

    // Menampilkan detail booking berdasarkan kode_booking
    public function showDetails($kode_booking)
    {
        if (!$kode_booking) {
            return redirect()->route('front_office.dashboard')->with('error', 'Kode booking tidak valid.');
        }

        $apiUrl = "https://event.mcc.or.id/api/event";
        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
        ])->withoutVerifying()->get($apiUrl);

        if ($response->successful()) {
            $data = collect($response->json()['data']);
            $booking = $data->firstWhere('booking_code', $kode_booking);

            if ($booking) {
                $bookingItems = collect($booking['booking_items'] ?? []);

                // Ambil ruangan dari key 'ruangans'
                $room = collect($booking['ruangans'] ?? [])->first();

                if (!$room) {
                    return redirect()->route('front_office.dashboard')->with('error', 'Data ruangan tidak ditemukan.');
                }

                $roomDetails = [
                    'room_name' => $room['name'] ?? 'Tidak Diketahui',
                    'room_floor' => $room['floor'] ?? 'Tidak Diketahui',
                    'room_description' => $room['description'] ?? '',
                    'room_facility' => $room['facility'] ?? '',
                ];

                return view('booking.details', compact('booking', 'bookingItems', 'roomDetails'));
            }

            return redirect()->route('front_office.dashboard')->with('error', 'Data booking tidak ditemukan.');
        }

        return view('errors.generic', [
            'error' => 'Tidak dapat mengambil data dari API. Silakan coba lagi nanti.',
        ]);
    }
}
