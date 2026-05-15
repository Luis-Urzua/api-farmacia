<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedido';
    protected $primaryKey = 'id_pedido';

    protected $fillable = [
        'fecha',
        'total',
        'estado',
        'id_usuario',
        'transaction_id',
        'estado_pago',
        'fecha_pago'
    ];

    public function detalles()
    {
        return $this->hasMany(Detalle::class, 'id_pedido');
    }
}