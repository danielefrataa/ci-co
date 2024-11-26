<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeminjamanBarang;
use App\Models\Booking;
use App\Models\Absen; // Pastikan model Absen telah diimpor
use Illuminate\Support\Facades\Log;

class PeminjamanController extends Controller
{
    public function show($kode_booking)
    {
        Log::info("Memulai pengecekan untuk kode booking: {$kode_booking}");

        // Ambil semua data peminjaman berdasarkan kode_booking
        $peminjamans = PeminjamanBarang::where('kode_booking', $kode_booking)->get();

        if ($peminjamans->isEmpty()) {
            Log::warning("Peminjaman tidak ditemukan untuk kode_booking: {$kode_booking}");
            return redirect('/')->with('error', 'Peminjaman tidak ditemukan.');
        }

        Log::info("Peminjaman ditemukan: ", ['peminjamans' => $peminjamans]);

        // Ambil data booking terkait peminjaman (jika tersedia)
        $booking = Booking::where('kode_booking', $kode_booking)->first();
        
        if (!$booking) {
            Log::warning("Data booking tidak ditemukan untuk kode_booking: {$kode_booking}");
            return redirect('/')->with('error', 'Booking tidak ditemukan.');
        }

        Log::info("Booking ditemukan: ", ['booking' => $booking]);

        // Ambil tanda tangan (signature) dari tabel absen
        $absen = Absen::where('id_booking', $kode_booking)->first();

        // Siapkan data tambahan untuk ditampilkan di view
        $data = [
            'peminjamans' => $peminjamans,
            'nama_event' => $booking->nama_event ?? 'Tidak Tersedia',
            'ruangan' => $booking->nama_ruangan ?? 'Tidak Tersedia',
            'pic' => $booking->nama_pic ?? 'Tidak Tersedia',
            'tanggal' => $booking->tanggal ?? 'Tidak Tersedia',
            'jam' => $booking->waktu ?? 'Tidak Tersedia',
            'signature' => $absen->signature ?? 'Tidak Tersedia', // Tambahkan signature dari absen
            'name' => $absen->name ?? 'tidak tersedia'
        ];

        Log::info("Data untuk ditampilkan di view: ", $data);

        // Tampilkan view dengan data peminjaman
        return view('peminjaman.showPinjam', $data);
    }

    public function edit($kode_booking)
    {
        $booking = Booking::select('id', 'nama_event', 'nama_organisasi', 'tanggal', 'ruangan', 'waktu_mulai', 'waktu_selesai', 'nama_pic', 'kode_booking')->get();
        // Pass $booking to the edit view for editing.
        return view('peminjaman.edit', compact('booking'));
    }
    public function showEdit($nama_event)
    {

        // Fetch the booking record based on nama_event
        $booking = Booking::where('nama_event', $nama_event)->first();

        // Check if booking is found
        // if ($booking) {
        //     // Access kode_booking directly from the booking record
        //     $kode_booking = $booking->kode_booking;

        //     // Redirect to the edit route, passing kode_booking as a parameter
        //     return redirect()->route('peminjaman.create', ['kode_booking' => $kode_booking]);
        //     //return redirect()->route('peminjaman.create', ['kode_booking' => $booking->kode_booking]);
        // } else {
        //     // Handle case if no booking found
        //     return redirect()->back()->with('error', 'Event not found.');
        // }
        $kodebook = $booking->kode_booking;

        Log::info("Memulai pengecekan untuk kode booking: {$kodebook}");

        // Ambil semua data peminjaman berdasarkan kode_booking
        $peminjamans = PeminjamanBarang::where('kode_booking', $kodebook)->get();

        if ($peminjamans->isEmpty()) {
            Log::warning("Peminjaman tidak ditemukan untuk kode_booking: {$kodebook}");
            return redirect('/')->with('error', 'Peminjaman tidak ditemukan.');
        }

        Log::info("Peminjaman ditemukan: ", ['peminjamans' => $peminjamans]);

        // Ambil data booking terkait peminjaman (jika tersedia)
        // $booking = Booking::where('kode_booking', $kode_booking)->first();

        // Ambil tanda tangan (signature) dari tabel absen
        $absen = Absen::where('id_booking', $kodebook)->first();

        // Siapkan data tambahan untuk ditampilkan di view
        $data = [
            'peminjamans' => $peminjamans,
            'nama_event' => $booking->nama_event ?? 'Tidak Tersedia',
            'ruangan' => $booking->ruangan ?? 'Tidak Tersedia',
            'pic' => $booking->nama_pic ?? 'Tidak Tersedia',
            'tanggal' => $booking->tanggal ?? 'Tidak Tersedia',
            'jam' => isset($booking->waktu_mulai, $booking->waktu_selesai)
                ? date('H:i', strtotime($booking->waktu_mulai)) . " - " . date('H:i', strtotime($booking->waktu_selesai))
                : 'Tidak Tersedia',
            'signature' => $absen->signature ?? 'Tidak Tersedia', // Tambahkan signature dari absen
            'name' => $absen->name ?? 'tidak tersedia'
        ];

        Log::info("Data untuk ditampilkan di view: ", $data);

        // Tampilkan view dengan data peminjaman
        return view('peminjaman.create', $data);
    }
    

public function store(Request $request)
{
    // Ambil data booking berdasarkan kode booking
    $booking = Booking::where('kode_booking', $request->kode_booking)->first();

    if (!$booking) {
        return redirect()->back()->with('error', 'Booking tidak ditemukan.');
    }

    // Menyimpan beberapa item barang
    foreach ($request->input('items') as $item) {
        $newItem = new PeminjamanBarang;
        $newItem->nama_item = $item['nama_item'];
        $newItem->jumlah = $item['jumlah'];
        $newItem->lokasi = $item['lokasi'];
        $newItem->kode_booking = $request->kode_booking; // Pastikan kode_booking juga diset
        $newItem->save();
    }

    return redirect()->route('bookings.index')->with('success', 'Booking berhasil ditambahkan.');
}

}