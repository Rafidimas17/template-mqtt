<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MqttData extends Model
{
    use HasFactory;

    protected $fillable = [
        'kendaraan_id', // Gunakan nama kolom yang benar
        'longitude',
        'latitude',
        'tanggal',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }
}
