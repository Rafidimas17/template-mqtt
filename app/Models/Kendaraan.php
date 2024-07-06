<?php

// app/Models/Kendaraan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    // Jika nama tabel tidak sesuai dengan konvensi Laravel
    protected $table = 'kendaraan'; // Nama tabel di database

    // Kolom yang bisa diisi
    protected $fillable = [
        'nama',
        'merk',
        'nomor_plat',
        'status',
    ];
}
