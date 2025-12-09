<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            TipoReservaSeeder::class,
            ZonaSeeder::class,
            HotelSeeder::class,
            ViajeroSeeder::class,
            VehiculoSeeder::class,
            PreciosSeeder::class,
            TransferReservaSeeder::class,
        ]);
    }
}
