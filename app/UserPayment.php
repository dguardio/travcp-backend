<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserPayment extends Model
{
    // for the currency column, possible currencies are: naira, dollar, pound

    protected $table = "user_payments";

    public static function getBySearch(Request $request){
        return self::when($request->description, function($query) use($request){
            return $query->where('description',"LIKE", "%{$request->description}%");
        })->when($request->user_id, function($query)  use($request){
            return $query->where('user_id', '=', $request->user_id);
        })->when($request->experience_id, function($query)  use($request){
            return $query->where('experience_id', '=', $request->experience_id);
        })->when($request->min_amount, function($query)  use($request){
            return $query->where('amount', '>=', $request->min_amount);
        })->when($request->max_amount, function($query)  use($request){
            return $query->where('amount', '=', $request->max_amount);
        })->when($request->amount, function($query)  use($request){
            return $query->where('amount', '=', $request->amount);
        })->when($request->currency, function($query)  use($request){
            return $query->where('currency', '=', $request->currency);
        });
    }

    protected $guarded = [];

}
