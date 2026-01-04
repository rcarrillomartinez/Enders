<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SistemaNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $titulo;
    public $mensaje;
    public $datos;

    public function __construct($titulo, $mensaje, $datos = [])
    {
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
        $this->datos = $datos;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->titulo,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notificacion_general', 
        );
    }
}