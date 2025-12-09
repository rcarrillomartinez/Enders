<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehiculo;

class VehiculoSeeder extends Seeder
{
    public function run()
    {
        $vehiculos = [
            ['descripcion' => 'Toyota Corolla Rojo', 'capacidad' => 4, 'email_conductor' => 'conductor1@example.com', 'password' => '1234'],
            ['descripcion' => 'Ford Fiesta Azul', 'capacidad' => 4, 'email_conductor' => 'conductor2@example.com', 'password' => '1234'],
            ['descripcion' => 'Renault Clio Blanco', 'capacidad' => 4, 'email_conductor' => 'conductor3@example.com', 'password' => '1234'],
        ];

        foreach ($vehiculos as $vh) {
            Vehiculo::updateOrCreate(['email_conductor' => $vh['email_conductor']], $vh);
        }
    }
}
