<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detalle extends Model
{
    protected $table = 'detalle';
    protected $primaryKey = 'id_detalle';

    protected $fillable = [
        'id_pedido',
        'id_medicamento',
        'cantidad',
        'precio_unitario'
    ];

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class, 'id_medicamento');
    }
}