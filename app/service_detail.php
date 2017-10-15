<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class service_detail extends Model
{
    protected $table = "services_detail";
    public $timestamps = false;
    protected $fillable = [
        'serviceid','encargado','horario','precio_base'
    ];
}
