<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = "orders";
    public $timestamps = false;
    protected $fillable = [
        'orderid','userid','orderdate','status','userA','total','subtotal','taxes'
    ];
}
