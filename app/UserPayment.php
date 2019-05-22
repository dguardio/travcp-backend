<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserPayment extends Model
{
    // for the currency column, possible currencies are: naira, dollar, pound

    protected $table = "user_payments";

    public static function getBySearch(Request $request){
        return self::when($request->desciption, function($query) use($request){
            return $query->where('desciption',"LIKE", "%{$request->desciption}%");
        })->when($request->user_id, function($query)  use($request){
            return $query->where('user_id', '=', $request->user_id);
        })->when($request->experience_id, function($query)  use($request){
            return $query->where('experience_id', '=', $request->experience_id);
        })->when($request->min_amount, function($query)  use($request){
            return $query->where('amount', '>=', $request->min_amount);
        })->when($request->max_amount, function($query)  use($request){
            return $query->where('amount', '=', $request->max_amount);
        })->when($request->currency, function($query)  use($request){
            return $query->where('currency', '=', $request->currency);
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
    protected $guarded = [];

}
