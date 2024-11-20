<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DutyOfficer extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'duty_officer';

    // Primary key
    protected $primaryKey = 'id';

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'nama_do',
        'no_hp',
    ];

    // Jika tabel tidak memiliki kolom timestamps (created_at, updated_at)
    public $timestamps = false;
}
    