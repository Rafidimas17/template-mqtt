<?php

// database/seeders/KendaraanSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KendaraanSeeder extends Seeder
{
    public function run()
    {
        DB::table('kendaraan')->insert([
            'id' => 1,
            'nama' => 'SB1',
            'merk' => 'Mercedez Benz',
            'nomor_plat' => 'L9087KOI',
            'status' => 'aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

