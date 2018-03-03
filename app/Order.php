<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Date\Date;
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
    
    protected function getDateFormat(){
        return 'Y-m-d H:i:s+';
    }

    public function orderDetail(){
        return $this->hasMany('App\OrderDetail','orderid','id');
    }
    public function user(){
        return $this->belongsTo('App\Usuarios','userid');
    }

    public function getCreatedAtAttribute($date){
        return new Date($date);
    }
    
}