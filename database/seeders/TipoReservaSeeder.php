<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoReserva;

class TipoReservaSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['nombre' => 'Ida', 'descripcion' => 'Solo llegada/entrada'],
            ['nombre' => 'Vuelta', 'descripcion' => 'Solo salida'],
            ['nombre' => 'Ida y Vuelta', 'descripcion' => 'Llegada y salida']
        ];

        foreach ($types as $t) {
            TipoReserva::updateOrCreate(['nombre' => $t['nombre']], $t);
        }
    }
}
