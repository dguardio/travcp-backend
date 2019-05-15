<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = "cart_items";

    protected $guarded = [];

    public function cart(){
        $this->belongsTo("App\Cart");
    }

    public function booking(){
        $this->belongsTo("App\Booking");
    }
}