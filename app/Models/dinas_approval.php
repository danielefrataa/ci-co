<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dinas_approval extends Model
{
    use HasFactory;

    protected $table = 'dinas_approval';

    protected $fillable = [
        'id_booking',
        'created_at',
        'kadin_approval',
        'kabin_approval',
    ];
}
