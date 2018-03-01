<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
  protected $table = 'price';
  protected $fillable = [
      'price1', 'price2', 'price3', 'price4', 'price5'
  ];
  public $timestamps = false;  
}
