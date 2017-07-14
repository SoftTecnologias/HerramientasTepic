<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
   protected $table = 'brand';
   protected $fillable = [
       'name', 'logo', 'authorized', 'total_sales'
   ];
   public $timestamps = false;
}
