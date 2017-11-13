<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class canceldetail extends Model
{
    protected $table = "cancel_detail";
    public $timestamps = false;
    protected $fillable = [
        'orderid','detalle'
    ];
}
