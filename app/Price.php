<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{ protected $table = "price";
    public $timestamps = false;
    protected $fillable = [
        'price1','price2','price3','price4','price5'
    ];

    public function getPriceAttribute($userprice){
    }

    public function scopePrice($userprice){
        switch ($userprice){
            case 1:
                return $this->price1;
                break;
            case 2:
                return $this->price2;
                break;
            case 3:
                return $this->price3;
                break;
            case 4:
                return $this->price4;
                break;
            case 5:
                return $this->price5;
                break;
            default:
                return $this->price1;
                break;
        }
    }
}
