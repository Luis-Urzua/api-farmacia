<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model
{
    use HasFactory;

    protected $table = 'medicamentos';

    protected $primaryKey = 'id_medicamento';

    protected $fillable = [
        'id_medicamento',
        'nombre',
        'descripcion',
        'precio',
        'receta',
        'fecha_vencimiento',
        'id_categoria',
        'imagen1',
        'imagen2',
        'imagen3',
        'existencia'
    ];
}