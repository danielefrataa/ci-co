<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'absen';

    protected $fillable = [
        'status',
        'id_booking',
        'ruangan',
        'lantai'
    ];



    // Accessor to get combined data

}
