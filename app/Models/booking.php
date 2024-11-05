<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class booking extends Model
{
    use HasFactory;
    protected $table = 'booking'; // Tambahkan ini jika nama tabel kamu tanpa

    protected $fillable = [
        'id',
        'kode_booking',
        'nama_event',
        'ruangan',
        'waktu_mulai',
        'waktu_selesai',
        'user_name',
        'status'
    ];
    protected $guarded = ['id'];

    public function getWaktuAttribute()
    {
        return $this->waktu_mulai . ' - ' . $this->waktu_selesai;
    }
    
   // Model Bookin
public function peminjaman()
{
    return $this->hasMany(PeminjamanBarang::class, 'kode_booking', 'kode_booking');
}

    
    
}
