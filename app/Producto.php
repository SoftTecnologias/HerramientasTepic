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
        'longdescription', 'reorderpoint','photo','photo2','photo3','selected'
    ];

    public function price(){
        return $this->hasOne('App\Price','id','priceid');
    }

}
