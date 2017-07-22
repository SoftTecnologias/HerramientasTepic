<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle_movimiento extends Model
{
    protected $table = 'detalle_movimientos';
    protected $fillable =[
        'movimiento_id','producto_id','cantidad','importe','total'
    ];
}
