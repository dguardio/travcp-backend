<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MerchantPayment extends Model
{
    // for the currency column, possible currencies are: naira, dollar, pound
    protected $table = "merchant_payments";

    protected $guarded = [];

    public static function getBySearch(Request $request){
        return self::when($request->description, function($query) use($request){
            return $query->where('description',"LIKE", "%{$request->description}%");
        })->when($request->payer_id, function($query)  use($request){
            return $query->where('payer_id', '=', $request->payer_id);
        })->when($request->merchant_id, function($query)  use($request){
            return $query->where('merchant_id', '=', $request->merchant_id);
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
}
