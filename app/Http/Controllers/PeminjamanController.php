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
    public function update(Request $request, $id)
{
    // Cek apakah data booking sudah ada berdasarkan ID
    $booking = Booking::find($id);
    
    if ($booking) {
        // Jika data ditemukan, lakukan update
        $booking->update([
            'nama' => $request->input('nama'),
            // tambahkan kolom lain yang perlu diupdate
        ]);

        return back()->with('success', 'Booking berhasil diupdate.');
    } else {
        // Jika data tidak ditemukan, lakukan create data baru
        Booking::create([
            'id' => $id, // Gunakan ID yang diberikan jika perlu
            'nama' => $request->input('nama'),
            // tambahkan kolom lain yang diperlukan
        ]);

        return back()->with('success', 'Booking baru berhasil ditambahkan.');
    }
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


public function showEdit($nama_event)
{
    // Fetch the booking record based on nama_event
    $booking = Booking::where('nama_event', $nama_event)->first();

    // Cek apakah booking ditemukan
    if (!$booking) {
        return redirect()->back()->with('error', 'Event tidak ditemukan.');
    }

    // Ambil kode booking dari booking yang ditemukan
    $kode_booking = $booking->kode_booking;

    // Cek peminjaman berdasarkan kode booking
    $peminjamans = PeminjamanBarang::where('kode_booking', $kode_booking)->get();

    // Jika peminjaman tidak ditemukan
    if ($peminjamans->isEmpty()) {
        return redirect('/')->with('error', 'Peminjaman tidak ditemukan.');
    }

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
        'signature' => $absen->signature ?? 'Tidak Tersedia',
        'name' => $absen->name ?? 'tidak tersedia',
        'kode_booking' => $kode_booking // Pastikan kode_booking ada di sini

    ];

    // Tampilkan view dengan data peminjaman
    return view('peminjaman.create', $data);
}

}
