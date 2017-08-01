<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    protected $table = "localidades";

    protected $fillable = ['nombre','id_localidad','municipio_id' ];
    public $timestamps = false;
}
