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
    protected $dates = ['created_at','updated_at'];
    

    public function orderDetail(){
        return $this->hasMany('App\OrderDetail','orderid','id');
    }
    public function user(){
        return $this->belongsTo('App\Usuarios','userid');
    }
    
}