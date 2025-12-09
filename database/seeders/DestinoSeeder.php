<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Destino;

class DestinoSeeder extends Seeder
{
    public function run()
    {
        $items = [
            ['nombre' => 'Aeropuerto', 'descripcion' => 'Aeropuerto principal'],
            ['nombre' => 'Centro Ciudad', 'descripcion' => 'Centro urbano'],
            ['nombre' => 'Puerto', 'descripcion' => 'Zona portuaria']
        ];

        foreach ($items as $i) {
            Destino::updateOrCreate(['nombre' => $i['nombre']], $i);
        }
    }
}
