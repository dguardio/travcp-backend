<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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

    public static function getBySearch(Request $request){
        return self::when($request->order_id, function($query) use($request){
            return $query->where('order_id', '=', $request->order_id);
        })->when($request->booking_id, function($query)  use($request){
            return $query->where('booking_id', '=', $request->booking_id);
        });
    }
}
