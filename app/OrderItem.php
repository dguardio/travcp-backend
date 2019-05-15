<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = "order_items";

    protected $guarded = [];

    public function booking(){
        return $this->belongsTo("App\Booking");
    }

    public function order(){
        return $this->belongsTo("App\Order");
    }
}
