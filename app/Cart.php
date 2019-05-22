<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Cart extends Model
{
    protected $table = "carts";

    protected $guarded = [];


    public function user(){
        return $this->belongsTo("App\User");
    }

    public function items(){
        return $this->hasMany("App\CartItem");
    }
}
