<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class booking extends Model
{
    use HasFactory;
    protected $table = 'booking'; // Tambahkan ini jika nama tabel kamu tanpa 's'

    protected $guarded = ['id'];

}
