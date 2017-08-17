<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrecioEnvio extends Model
{
    protected $table = 'CostoEnvio';
    protected $fillable = [
        'codigo_postal', 'Nombre_Colonia', 'costo_envio'
    ];
}
