<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    protected $table = "subcategory";
    protected $fillable =  [
        'name', 'categoryid'
    ];
    public $timestamps = false;
}
