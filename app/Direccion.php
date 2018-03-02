<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    protected $table = "address";

    protected $fillable = ['addressid'
        ,'street'
        ,'streetnumber'
        ,'street2'
        ,'street3'
        ,'neigborhood'
        ,'zipcode'
        ,'city'
        ,'country'
        ,'state'
        ,'region'
        ,'reference'
        ,'userid'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\Usuarios','userid');
    }
}
