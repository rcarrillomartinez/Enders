<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Authenticatable
{
    use Notifiable;

    protected $table = 'tranfer_hotel';
    protected $primaryKey = 'id_hotel';
    public $timestamps = false;

    protected $fillable = [
        'usuario',
        'password',
        'nombre_hotel',
        'id_zona',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Relación: Un hotel tiene muchas reservas
     */
    public function reservas(): HasMany
    {
        return $this->hasMany(TransferReserva::class, 'id_hotel', 'id_hotel');
    }

    /**
     * Relación: Un hotel tiene muchos vehículos
     */
    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class, 'id_hotel', 'id_hotel');
    }
}
