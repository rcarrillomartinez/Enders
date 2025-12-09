<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoReserva extends Model
{
    protected $table = 'tipo_reserva';
    protected $primaryKey = 'id_tipo_reserva';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Relación: Un tipo de reserva tiene muchas reservas
     */
    public function reservas(): HasMany
    {
        return $this->hasMany(TransferReserva::class, 'id_tipo_reserva', 'id_tipo_reserva');
    }
}
