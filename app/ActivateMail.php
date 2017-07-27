<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivateMail extends Model
{
    protected $table = "activate_email";
    public $timestamps = false;
    protected $fillable = [
        'user_id'
    ];
}
