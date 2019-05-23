<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CartItem extends Model
{
    protected $table = "cart_items";

    protected $guarded = [];

    /**
     * get list of cart items by parameters
     * @param Request $request
     * @return mixed
     */
    public static function getBySearch(Request $request){
        return self::when($request->booking_id, function($query) use($request){
            return $query->where('booking_id', '=', $request->booking_id);
        })->when($request->cart_id, function($query)  use($request){
            return $query->where('cart_id', '=', $request->cart_id);
        });
    }

    public function cart(){
        return $this->belongsTo("App\Cart");
    }

    public function booking(){
        return $this->belongsTo("App\Booking");
    }
}