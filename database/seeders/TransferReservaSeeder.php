<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransferReserva;
use Illuminate\Support\Str;

class TransferReservaSeeder extends Seeder
{
    public function run()
    {
        // Note: transfer_reservas table does not have id_viajero; use email_cliente + localizador
        $reservas = [
            [
                'localizador' => Str::upper(Str::random(8)),
                'id_hotel' => 1,
                'id_tipo_reserva' => 1,
                'email_cliente' => 'juan@example.com',
                'fecha_reserva' => now(),
                'fecha_entrada' => now()->addDays(7)->toDateString(),
                'hora_entrada' => '12:00:00',
                'numero_vuelo_entrada' => 'IB1234',
                'origen_vuelo_entrada' => 'MAD',
                'num_viajeros' => 2,
                'id_vehiculo' => 1,
                'estado' => 'confirmada',
                'nombre_cliente' => 'Juan',
                'apellido1_cliente' => 'Perez',
                'apellido2_cliente' => null,
            ],
            [
                'localizador' => Str::upper(Str::random(8)),
                'id_hotel' => 2,
                'id_tipo_reserva' => 2,
                'email_cliente' => 'maria@example.com',
                'fecha_reserva' => now()->addDay(),
                'fecha_entrada' => now()->addDays(10)->toDateString(),
                'hora_entrada' => '15:30:00',
                'numero_vuelo_entrada' => 'BA4321',
                'origen_vuelo_entrada' => 'BCN',
                'num_viajeros' => 1,
                'id_vehiculo' => 2,
                'estado' => 'pendiente',
                'nombre_cliente' => 'Maria',
                'apellido1_cliente' => 'Gomez',
                'apellido2_cliente' => null,
            ],
            [
                'localizador' => Str::upper(Str::random(8)),
                'id_hotel' => 3,
                'id_tipo_reserva' => 3,
                'email_cliente' => 'luis@example.com',
                'fecha_reserva' => now()->addDays(2),
                'fecha_entrada' => now()->addDays(14)->toDateString(),
                'hora_entrada' => '09:00:00',
                'numero_vuelo_entrada' => null,
                'origen_vuelo_entrada' => null,
                'num_viajeros' => 3,
                'id_vehiculo' => 3,
                'estado' => 'cancelada',
                'nombre_cliente' => 'Luis',
                'apellido1_cliente' => 'Sanchez',
                'apellido2_cliente' => null,
            ],
        ];

        foreach ($reservas as $r) {
            TransferReserva::updateOrCreate(['localizador' => $r['localizador']], $r);
        }
    }
}
