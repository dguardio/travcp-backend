<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Order extends Model
{
    protected $table = "orders";

    protected $guarded = [];

    public function items(){
        return $this->hasMany("App\OrderItem");
    }

    public static function getBySearch(Request $request){
        return self::when($request->user_id, function($query) use($request){
            return $query->where('user_id', '=', $request->user_id);
        })->when($request->min_price, function($query)  use($request){
            return $query->where('price', '>=', $request->min_price);
        })->when($request->max_price, function($query)  use($request){
            return $query->where('price', '<=', $request->max_price);
        });
    }
}
