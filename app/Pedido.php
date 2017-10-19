<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = "orders";
    public $timestamps = false;
    protected $fillable = [
        'status',
        'userA',
        'subtotal',
        'delivery_cost',
        'delivery_type',
        'userid',
        'step',
        'finished',
        'total',
        'taxes',
        'stripe_charge_id'
    ];
}
