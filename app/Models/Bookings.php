<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

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
}

