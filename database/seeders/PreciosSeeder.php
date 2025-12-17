<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreciosSeeder extends Seeder
{
    public function run()
    {
        // Sample pricing: price depends on vehicle and hotel
        // Assumes there are at least 3 vehicles and 3 hotels seeded earlier
        $prices = [
            ['id_vehiculo' => 1, 'id_hotel' => 1, 'precio' => 25],
            ['id_vehiculo' => 2, 'id_hotel' => 1, 'precio' => 35],
            ['id_vehiculo' => 3, 'id_hotel' => 2, 'precio' => 50],
            ['id_vehiculo' => 1, 'id_hotel' => 2, 'precio' => 30],
            ['id_vehiculo' => 2, 'id_hotel' => 3, 'precio' => 45],
        ];

        foreach ($prices as $p) {
            DB::table('transfer_precios')->updateOrInsert(
                ['id_vehiculo' => $p['id_vehiculo'], 'id_hotel' => $p['id_hotel']],
                array_merge($p, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
