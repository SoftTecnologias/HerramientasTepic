<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table="proveedores";
    protected $fillable =[
        "nombre","calle","colonia","numero_int","numero_ext"
        ,"cp","estado","municipio","telefono1","telefono2"
        ,"email" ,"contacto"
    ];
    protected  $hidden = [];

    public $timestamps = false;
}
