<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanBarang extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_barang';

    protected $fillable = [
        'kode_booking',
        'nama_item',
        'jumlah',
        'lokasi',
        'marketing',
        'FO'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
