<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Viajero extends Authenticatable
{
    use Notifiable;

    protected $table = 'transfer_viajeros';
    protected $primaryKey = 'id_viajero';
    public $timestamps = false;

    protected $fillable = [
        'email',
        'nombre',
        'apellido1',
        'apellido2',
        'direccion',
        'codigoPostal',
        'ciudad',
        'pais',
        'password',
        'foto'
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Relación: Un viajero tiene muchas reservas
     */
    public function reservas()
    {
        return $this->hasMany(TransferReserva::class, 'email_cliente', 'email');
    }
}
