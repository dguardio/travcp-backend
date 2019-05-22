<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Booking extends Model
{
    protected $table = "bookings";
    protected $guarded = [];

    public function experience(){
        return $this->belongsTo('App\Experience');
    }

    public function order_items(){
        return $this->hasMany("App\OrderItem");
    }

    public function cart_items(){
        return $this->hasMany("App\CartItem");
    }
}
