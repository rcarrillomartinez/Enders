<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use Illuminate\Support\Facades\Hash;

class HotelSeeder extends Seeder
{
    public function run()
    {
        $hotels = [
            ['usuario' => 'hotel_sol', 'password' => Hash::make('secret'), 'nombre_hotel' => 'Hotel Sol', 'id_zona' => 1, 'comision' => 10],
            ['usuario' => 'hotel_luna', 'password' => Hash::make('secret'), 'nombre_hotel' => 'Hotel Luna', 'id_zona' => 2, 'comision' => 10],
            ['usuario' => 'hotel_estrella', 'password' => Hash::make('secret'), 'nombre_hotel' => 'Hotel Estrella', 'id_zona' => 3, 'comision' => 10],
        ];

        foreach ($hotels as $h) {
            Hotel::updateOrCreate(['usuario' => $h['usuario']], $h);
        }
    }
}
