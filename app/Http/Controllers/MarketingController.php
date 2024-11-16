<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\booking;

class MarketingController extends Controller
{

    public function index(Request $request)
    {
        $query = booking::query()
        ->select(
            'id', 'nama_event', 'nama_organisasi', 'tanggal', 'nama_ruangan',
            'waktu_mulai', 'waktu_selesai', 'nama_pic', 'kategori_ekraf',
            'jumlah_peserta', 'no_pic', 'kategori_event'
        );

        $ruanganWaktu = explode(' - ', $request->input('ruangan_dan_waktu', ''));
        if (count($ruanganWaktu) === 2) {
            $query->whereRaw("CONCAT(ruangan_dan_waktu, ' - ', waktu_selesai) LIKE ?", ['%' . $request->input('ruangan_dan_waktu') . '%']);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('search')) {
            $query->where('nama_event', 'like', '%' . $request->search . '%')
                ->orWhere('nama_organisasi', 'like', '%' . $request->search . '%');
        }

        $booking = $query->orderBy('tanggal')->paginate(5);
        return view('marketing.peminjaman', compact('booking'));
    }
}
