<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = "order_detail";
    protected $fillable = [
        'qty','price','productid','orderid'
    ];

    public function product(){
        return $this->hasOne('App\Producto','id','productid');
    }

    public function order(){
    	return $this->hasMany('App\Order','id','orderid');
    }
}
