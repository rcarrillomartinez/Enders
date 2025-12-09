<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehiculo extends Model
{
    protected $table = 'transfer_vehiculo';
    protected $primaryKey = 'id_vehiculo';
    public $timestamps = false;

    protected $fillable = [
        'id_hotel',
        'tipo_vehiculo',
        'matricula',
        'capacidad',
    ];

    /**
     * Relación: Un vehículo pertenece a un hotel
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'id_hotel', 'id_hotel');
    }
}
