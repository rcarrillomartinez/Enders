<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZonaSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 8; $i++) {
            DB::table('transfer_zona')->updateOrInsert(
                ['id_zona' => $i],
                ['descripcion' => 'Zona ' . $i, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
