<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'roomlist';

    protected $fillable = [
        'nama_ruangan',
        'lantai',
        'waktu_mulai',
        'waktu_selesai',
        'status',
    ];


    public function bookingAbsen()
    {
        return $this->hasMany(Booking::class, 'room_id', 'id');
    }
    // Accessor to get combined data

}
