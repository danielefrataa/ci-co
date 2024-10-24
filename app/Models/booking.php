<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class booking extends Model
{
    use HasFactory;
    protected $table = 'booking'; // Tambahkan ini jika nama tabel kamu tanpa 's'

<<<<<<< Updated upstream
    protected $fillable = [
        'id',
    ];
=======
    protected $guarded = ['id'];
    public function peminjamanBarang()
    {
        return $this->hasMany(PeminjamanBarang::class);
    }
    
    
>>>>>>> Stashed changes
}
