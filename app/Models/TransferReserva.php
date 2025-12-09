<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferReserva extends Model
{
    protected $table = 'transfer_reservas';
    protected $primaryKey = 'id_reserva';
    public $timestamps = false;

    protected $fillable = [
        'localizador',
        'id_hotel',
        'id_tipo_reserva',
        'email_cliente',
        'fecha_reserva',
        'fecha_modificacion',
        'fecha_entrada',
        'hora_entrada',
        'numero_vuelo_entrada',
        'origen_vuelo_entrada',
        'fecha_vuelo_salida',
        'hora_partida',
        'num_viajeros',
        'id_vehiculo',
        'estado',
        'nombre_cliente',
        'apellido1_cliente',
        'apellido2_cliente',
    ];

    /**
     * Relación: Una reserva pertenece a un hotel
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'id_hotel', 'id_hotel');
    }

    /**
     * Relación: Una reserva pertenece a un tipo de reserva
     */
    public function tipoReserva(): BelongsTo
    {
        return $this->belongsTo(TipoReserva::class, 'id_tipo_reserva', 'id_tipo_reserva');
    }

    /**
     * Relación: Una reserva pertenece a un vehículo
     */
    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }

    /**
     * Genera un localizador único
     */
    public static function generateLocalizador(): string
    {
        return 'TR-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
    }
}
