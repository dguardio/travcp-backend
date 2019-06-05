<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Medal extends Model
{
    protected $table = "medals";

    protected $guarded = [];

    public static function getBySearch(Request $request){
        return self::when($request->name, function($query)  use($request){
            return $query->where('name', 'LIKE', "%{$request->name}%");
        })->when($request->max_reviews, function($query)  use($request){
            return $query->where('review_threshold', '<=', $request->max_reviews);
        })->when($request->min_reviews, function($query)  use($request){
            return $query->where('review_threshold', '>=', $request->min_reviews);
        })->when($request->threshold, function($query)  use($request){
            return $query->where('review_threshold', '=', $request->threshold);
        });
    }
}
