<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destino extends Model
{
    protected $table = 'destinos';
    protected $primaryKey = 'id_destino';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}
