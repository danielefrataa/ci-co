<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    use HasFactory;
    protected $table = 'absen'; // Tambahkan ini jika nama tabel kamu tanpa 's'
    protected $guarded = ['id'];
    
// In Absen model
public function dutyOfficer()
{
    return $this->belongsTo(DutyOfficer::class, 'duty_officer', 'nama_do'); // Menggunakan nama kolom 'duty_officer'
}


public function booking()
{
    return $this->belongsTo(Booking::class, 'id_booking', 'kode_booking');
}

}
