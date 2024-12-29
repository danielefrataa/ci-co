<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\dinas_approval;
use Carbon\Carbon;

class dinasApprovalController extends Controller
{
    //Bisa nggak
    private $apiKey = 'JUrrUHAAdBepnJjpfVL2nY6mx9x4Cful4AhYxgs3Qj6HEgryn77KOoDr6BQZgHU1';

    public function index(Request $request)
    {
        // Get the date from the request or default to today
        $filterDate = Carbon::parse($request->get('date', Carbon::now()->toDateString()));

        $allBookings = collect();
        $searchTerm = strtolower($request->get('search', ''));
        $approvalStatusFilter = $request->get('approval_status', null);
        $sortOrder = $request->get('sort', 'desc'); // Default ke 'desc'
        $page = 1;
        $maxPages = 5; // Limit the number of pages per date

        do {
            $url = "https://event.mcc.or.id/api/event?status=booked&date={$filterDate->toDateString()}&page={$page}";
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
            ])->withoutVerifying()->get($url);

            if ($response->successful()) {
                $data = collect($response->json()['data'] ?? []);
                $allBookings = $allBookings->merge($data);
                $page++;
            } else {
                report("Error accessing API for date {$filterDate->toDateString()}: " . $response->status());
                break;
            }
        } while ($data->isNotEmpty() && $page <= $maxPages);

        // Process and filter the data
        $filteredBookings = $allBookings->map(function ($item) use ($filterDate) {
            $bookingItems = collect($item['booking_items'] ?? []);

            // Filter booking items to only include items with a matching booking_date
            $matchingBookingItems = $bookingItems->filter(function ($bookingItem) use ($filterDate) {
                return $bookingItem['booking_date'] == $filterDate->toDateString();
            });

            // If no booking items match the filtered date, return null
            if ($matchingBookingItems->isEmpty()) {
                return null;
            }

            // Calculate start and end times
            $startTime = $matchingBookingItems->min('booking_hour');
            $endTime = $matchingBookingItems->max('booking_hour');

            $item['start_time'] = $startTime ? Carbon::createFromTime($startTime, 0)->format('H:i') : null;
            $item['end_time'] = $endTime ? Carbon::createFromTime($endTime, 0)->format('H:i') : null;

            // Extract ruangan IDs from the filtered booking items
            $ruanganIds = $matchingBookingItems->pluck('ruangan_id')->unique();

            // Filter ruangans to only include those matching the IDs
            $item['ruangans'] = collect($item['ruangans'] ?? [])
                ->filter(fn($ruangan) => $ruanganIds->contains($ruangan['id']))
                ->values()
                ->toArray();

            $item['booking_items'] = $matchingBookingItems->toArray();

            // Fetch related database items based on booking code
            $item['dinas_approval'] = dinas_approval::where('id_booking', $item['booking_code'])->first();
            $item['database_items'] = dinas_approval::where('id_booking', $item['booking_code'])->get();

            return $item;
        })->filter(function ($item) use ($searchTerm, $approvalStatusFilter) {
            if (empty($item['booking_items'])) {
                return false;
            }
            // Filter berdasarkan status persetujuan jika parameter diberikan
            if (!is_null($approvalStatusFilter)) {
                $approvalStatus = $item['dinas_approval']->kabin_approval ?? 0;
                if ($approvalStatus != $approvalStatusFilter) {
                    return false;
                }
            }
            if ($searchTerm) {
                // Convert the entire $item to a single string to search all fields
                $itemText = strtolower(json_encode($item));

                return strpos($itemText, $searchTerm) !== false;
            }

            return true;
        });


        // Sort by start time
        $filteredBookings = $filteredBookings->sortBy([
            // Booking yang sudah disetujui (kabin_approval != 0) ditempatkan di atas
            fn($booking) => $booking['dinas_approval']->kabin_approval ?? 0,
            'desc',
            // Urutkan berdasarkan waktu mulai
            ['start_time', $sortOrder]
        ]);

        // Pagination
        $currentPage = (int) $request->get('page', 1);
        $perPage = (int) $request->get('per_page', 6);
        $paginatedBookings = $filteredBookings->forPage($currentPage, $perPage);
        $dinasApprovals = dinas_approval::all(); // Sesuaikan query sesuai kebutuhan

        return view('dinas.approve', [
            'dinas_approval' => $dinasApprovals,
            'bookings' => $paginatedBookings,
            'totalPages' => ceil($filteredBookings->count() / $perPage),
            'currentPage' => $currentPage,
            'perPage' => $perPage,
            'filterDate' => $filterDate->toDateString(),
            'approvalStatusFilter' => $approvalStatusFilter,
            'sortOrder' => $sortOrder,
        ]);
    }
    public function approveKabid(Request $request)
    {
        // Validasi input untuk Kabid
        $validated = $request->validate([
            'id_booking' => 'required', // Pastikan id_booking ada di tabel bookings
            'kabin_approval' => 'required|in:0,1', // Kabid hanya bisa approve atau reject (0 atau 1)
        ]);
    
        // Cari data persetujuan berdasarkan id_booking
        $dinasApproval = dinas_approval::where('id_booking', $validated['id_booking'])->first();
    
        // Tentukan data yang akan di-update untuk Kabid
        $dataToUpdate = [
            'kabin_approval' => $validated['kabin_approval'],
        ];
    
        // Jika data persetujuan sudah ada, update; jika belum ada, buat data baru
        if ($dinasApproval) {
            // Update data persetujuan Kabid
            $dinasApproval->update($dataToUpdate);
        } else {
            // Jika belum ada, buat data persetujuan baru
            dinas_approval::create(array_merge($validated, $dataToUpdate));
        }
    
        // Redirect dengan pesan sukses
        return redirect()->route('dinas.approve')->with('success', 'Status persetujuan Kabid berhasil diperbarui.');
    }
    public function approveKadin(Request $request)
    {
        // Validasi input untuk Kadin
        $validated = $request->validate([
            'id_booking' => 'required', // Pastikan id_booking ada di tabel bookings
            'kadin_approval' => 'required|in:0,1', // Kadin hanya bisa approve atau reject (0 atau 1)
        ]);
    
        // Cari data persetujuan berdasarkan id_booking
        $dinasApproval = dinas_approval::where('id_booking', $validated['id_booking'])->first();
    
        // Tentukan data yang akan di-update untuk Kadin
        $dataToUpdate = [
            'kadin_approval' => $validated['kadin_approval'],
        ];
    
        // Jika data persetujuan sudah ada, update; jika belum ada, buat data baru
        if ($dinasApproval) {
            // Update data persetujuan Kadin
            $dinasApproval->update($dataToUpdate);
        } else {
            // Jika belum ada, buat data persetujuan baru
            dinas_approval::create(array_merge($validated, $dataToUpdate));
        }
    
        // Redirect dengan pesan sukses
        return redirect()->route('dinas.approve')->with('success', 'Status persetujuan Kadin berhasil diperbarui.');
    }
        
   
}
