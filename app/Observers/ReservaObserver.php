<?php

namespace App\Observers;

use App\Models\TransferReserva;
use App\Mail\SistemaNotification;
use Illuminate\Support\Facades\Mail;

class ReservaObserver
{
    /**
     * Se ejecuta cuando se crea una nueva reserva.
     */
    public function created(TransferReserva $reserva)
    {
        $titulo = "Confirmación de Reserva: " . $reserva->localizador;
        
        $mensaje = "Hola " . ($reserva->nombre_cliente ?? 'cliente') . ",\n\n" .
                   "Tu reserva de transfer ha sido creada correctamente.\n" .
                   "Localizador: " . $reserva->localizador . "\n" .
                   "Fecha de entrada: " . ($reserva->fecha_entrada ? $reserva->fecha_entrada->format('d/m/Y H:i') : 'N/A');

        // Enviamos el email al cliente
        Mail::to($reserva->email_cliente)->send(new SistemaNotification($titulo, $mensaje, $reserva->toArray()));
    }

    /**
     * Se ejecuta cuando se actualiza la reserva (ej: cambio de estado).
     */
    public function updated(TransferReserva $reserva)
    {
        // Si lo que ha cambiado es el estado (pendiente -> confirmada, etc.)
        if ($reserva->isDirty('estado')) {
            $titulo = "Actualización de estado - Reserva " . $reserva->localizador;
            $mensaje = "El estado de tu reserva ha cambiado a: " . strtoupper($reserva->estado);

            Mail::to($reserva->email_cliente)->send(new SistemaNotification($titulo, $mensaje, $reserva->toArray()));
        }
    }
}