<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = "cart_items";

    protected $guarded = [];

    public function cart(){
        return $this->belongsTo("App\Cart");
    }

    public function booking(){
        return $this->belongsTo("App\Booking");
    }
}