<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = "product";
    public $timestamps = false;
    protected $fillable = [
        'code', 'name', 'stock', 'currency', 'brandid',
        'subcategoryid','categoryid','priceid', 'shortdescription',
        'longdescription', 'reorderpoint','photo','photo2','photo3'
    ];

}
