<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "orders";
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
        'stripe_charge_id',
    ];

    public function orderDetail(){
	return $this->belongsTo('App\Order');
    }   
}
