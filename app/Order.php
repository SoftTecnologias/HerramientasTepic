<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "orders";
    protected $fillable = [
        'orderdate','status','userA','total','subtotal','taxes', 'delivery_cost',
        'delivery_type','userid'
    ];
}
