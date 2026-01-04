<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Importa los Modelos (Verifica que estén en App\Models)
use App\Models\TransferReserva;
use App\Models\Viajero;

// Importa los Observers (Verifica que estén en App\Observers)
use App\Observers\ReservaObserver;
use App\Observers\ViajeroObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Registramos los observadores solo si las clases existen
        TransferReserva::observe(ReservaObserver::class);
        Viajero::observe(ViajeroObserver::class);
    }
}