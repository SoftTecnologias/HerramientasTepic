<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = "banner_principal";
    public $timestamps = false;
    protected $fillable = [
        'titulo','contenido','img'
    ];
}
