<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'kode_booking',
        'nama_event',
        'nama_ruangan',
        'lantai',
        'waktu_mulai',
        'waktu_selesai',
        'user_name',
        'nama_pic',
        'phone',
        'status'
    ];
}

