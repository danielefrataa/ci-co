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



    // Accessor to get combined data

}
