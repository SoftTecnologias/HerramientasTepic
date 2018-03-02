<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model{
    protected $table = "users";
    protected $fillable=[
         "name", "lastname", "password", "phone",
        "signindate", "roleid", "userprice", "status", "apikey", "username"
    ];
    protected $hidden = [];

    public $timestamps = false;

    public function order(){
        return $this->hasOne('App\Order','userid','id');
    }
    public function address(){
        return $this->hasOne('App\Direccion','userid','id');
    }
}
