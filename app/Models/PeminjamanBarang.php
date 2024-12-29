<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class PeminjamanBarang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'peminjaman_barang';

    protected $fillable = [
        'kode_booking',
        'nama_item',
        'jumlah',
        'marketing',
        'FO',
        'created_by',  // Add created_by
        'deleted_by',  // Add deleted_by
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    public function absen()
    {
        return $this->hasOne(Absen::class, 'id_booking', 'kode_booking'); // Sesuaikan dengan kolom yang sesuai
    }
    // Di model PeminjamanBarang
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

}
