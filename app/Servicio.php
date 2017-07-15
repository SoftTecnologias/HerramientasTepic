<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
  protected $table = "services";
    public $timestamps = false;
    protected $fillable = [
       'title','shortdescription','longdescription','img','show'
    ];
}
