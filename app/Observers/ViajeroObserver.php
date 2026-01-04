<?php

namespace App\Observers;

use App\Models\Viajero;
use App\Mail\SistemaNotification;
use Illuminate\Support\Facades\Mail;

class ViajeroObserver
{
    /**
     * Se ejecuta cuando un nuevo viajero se registra.
     */
    public function created(Viajero $viajero)
    {
        $titulo = "¡Bienvenido a Enders Transfer!";
        $mensaje = "Hola " . $viajero->nombre . ",\n\n" .
                   "Gracias por registrarte en nuestra plataforma. " .
                   "Ahora puedes gestionar todas tus reservas de transfer de forma sencilla.";

        Mail::to($viajero->email)->send(new SistemaNotification($titulo, $mensaje));
    }
}