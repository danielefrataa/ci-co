<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class booking extends Model
{
    use HasFactory;
    protected $table = 'booking'; // Tambahkan ini jika nama tabel kamu tanpa 's'

    protected $fillable = [
        'id',
    ];
    protected $guarded = ['id'];
   // Model Booking
public function peminjaman()
{
    return $this->hasMany(PeminjamanBarang::class, 'kode_booking', 'kode_booking');
}

    
    
}
