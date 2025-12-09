<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'transfer_admin';
    protected $primaryKey = 'id_admin';
    public $timestamps = false;

    protected $fillable = [
        'email',
        'password',
        'nombre',
    ];

    protected $hidden = [
        'password',
    ];
}
