<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Viajero;
use Illuminate\Support\Facades\Hash;

class ViajeroSeeder extends Seeder
{
    public function run()
    {
        $viajeros = [
            [
                'email' => 'juan@example.com',
                'nombre' => 'Juan',
                'apellido1' => 'Perez',
                'apellido2' => null,
                'direccion' => 'C/ Falsa 1',
                'codigoPostal' => '28001',
                'ciudad' => 'Madrid',
                'pais' => 'España',
                'password' => Hash::make('secret'),
            ],
            [
                'email' => 'maria@example.com',
                'nombre' => 'Maria',
                'apellido1' => 'Gomez',
                'apellido2' => null,
                'direccion' => 'C/ Real 2',
                'codigoPostal' => '08001',
                'ciudad' => 'Barcelona',
                'pais' => 'España',
                'password' => Hash::make('secret'),
            ],
            [
                'email' => 'luis@example.com',
                'nombre' => 'Luis',
                'apellido1' => 'Sanchez',
                'apellido2' => null,
                'direccion' => 'Av. Norte 3',
                'codigoPostal' => '41001',
                'ciudad' => 'Sevilla',
                'pais' => 'España',
                'password' => Hash::make('secret'),
            ],
        ];

        foreach ($viajeros as $v) {
            Viajero::updateOrCreate(['email' => $v['email']], $v);
        }
    }
}
