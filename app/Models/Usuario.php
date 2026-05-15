<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'usuario';

    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nombre',
        'correo',
        'contrasena',
        'rol',
        'imagen'
    ];

    protected $hidden = [
        'contrasena'
    ];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}