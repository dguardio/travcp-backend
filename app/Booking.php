<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Booking extends Model
{
    protected $table = "bookings";
    protected $guarded = [];


    /**
     * get list of bookings by parameters
     * @param Request $request
     * @return mixed
     */
    public static function getBySearch(Request $request){
        return self::when($request->min_price, function($query) use($request){
            return $query->where('price', '>=', $request->min_price);
        })->when($request->max_price, function($query)  use($request){
            return $query->where('price', '<=', $request->max_price);
        })->when($request->merchant_id, function($query)  use($request){
            return $query->where('merchant_id', '=', $request->merchant_id);
        })->when($request->user_id, function($query)  use($request){
            return $query->where('user_id', '=', $request->user_id);
        })->when($request->currency, function($query)  use($request){
            return $query->where('currency', '=', $request->currency);
        })->when($request->start_date, function($query)  use($request){
            return $query->where('start_date', '=', $request->start_date);
        })->when($request->end_date, function($query)  use($request){
            return $query->where('end_date', '=', $request->end_date);
        })->when($request->experience_id, function($query)  use($request){
            return $query->where('experience_id', '=', $request->experience_id);
        })->when($request->delivered, function($query)  use($request){
            $delivered = $request->delivered ? true: false;
            return $query->where('delivered', '=', $delivered);
        })->when($request->paid, function($query)  use($request){
            $paid = $request->paid ? true: false;
            return $query->where('paid', '=', $paid);
        })->when($request->experienced, function($query)  use($request){
            $experienced = $request->experienced ? true: false;
            return $query->where('experienced', '=', $experienced);
        });
    }

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
