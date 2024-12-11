<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class list_barang extends Model
{
    use HasFactory;

    // Table name (if it doesn't follow Laravel's convention)
    protected $table = 'list_barang';

    // Specify the columns that can be mass assigned
    protected $fillable = ['nama_barang', 'jumlah', 'satuan'];

    // Optional: Primary key column name
    protected $primaryKey = 'id';

    // Optional: Disable timestamps if not used
    public $timestamps = false;
}
