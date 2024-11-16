<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Room;
class booking extends Model
{
    use HasFactory;

    protected $table = 'booking';

    protected $fillable = [
        'id',
        'nama_event', 
        'kode_booking', 
        'nama_organisasi', 
        'tanggal', 
        'ruangan', 
        'waktu_mulai', 
        'waktu_selesai', 
        'nama_pic',
        'status'
    ];

    public function getWaktuAttribute()
    {
        return Carbon::parse($this->waktu_mulai)->format('H:i') . ' - ' . Carbon::parse($this->waktu_selesai)->format('H:i');
    }

    public function absen()
{
    return $this->hasMany(Absen::class, 'id_booking', 'kode_booking');
}

    

    public function peminjaman()
    {
        return $this->hasMany(PeminjamanBarang::class, 'kode_booking', 'kode_booking');
    }

    // Accessor for status from Absen table
    public function getAbsenStatusAttribute()
    {
        return $this->absen()->latest()->value('status');
    }
    public function ruangan()
    {
        return $this->belongsTo(Room::class); // Sesuaikan dengan nama model Ruangan
    }
}
