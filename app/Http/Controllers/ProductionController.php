<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\booking;

class ProductionController extends Controller
{
    public function index(Request $request)
    {
        $query = booking::query()
        ->select(
            'id', 'nama_event', 'nama_organisasi', 'tanggal', 'nama_ruangan',
            'waktu_mulai', 'waktu_selesai', 'nama_pic', 'kategori_ekraf',
            'jumlah_peserta', 'no_pic', 'kategori_event'
        );

        // Filter ruangan dan waktu jika ada input
        $ruanganWaktu = explode(' - ', $request->input('ruangan_dan_waktu', ''));
        if (count($ruanganWaktu) === 2) {
            $query->whereRaw("CONCAT(ruangan_dan_waktu, ' - ', waktu_selesai) LIKE ?", ['%' . $request->input('ruangan_dan_waktu') . '%']);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Filter pencarian
        $search = $request->get('search');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_event', 'LIKE', "%$search%")
                  ->orWhere('nama_pic', 'LIKE', "%$search%")
                  ->orWhere('nama_organisasi', 'LIKE', "%$search%");
            });
        }
        // Mengambil semua data dengan filter (jika ada)
        $booking = $query->orderBy('tanggal')->get();

        return view('production.index', compact('booking'));
    }
}
