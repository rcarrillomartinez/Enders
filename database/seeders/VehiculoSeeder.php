<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehiculo;

class VehiculoSeeder extends Seeder
{
    public function run()
    {
        $vehiculos = [
            ['tipo_vehiculo' => 'Turismo', 'matricula' => 'ABC123', 'capacidad' => 4, 'id_hotel' => 1],
            ['tipo_vehiculo' => 'Minivan', 'matricula' => 'DEF456', 'capacidad' => 8, 'id_hotel' => 1],
            ['tipo_vehiculo' => 'Autobus', 'matricula' => 'GHI789', 'capacidad' => 40, 'id_hotel' => 2],
        ];

        foreach ($vehiculos as $vh) {
            Vehiculo::updateOrCreate(['matricula' => $vh['matricula']], $vh);
        }
    }
}
