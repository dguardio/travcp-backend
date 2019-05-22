<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Review extends Model
{
    protected $table = "reviews";

    protected $guarded = [];

    public static function getBySearch(Request $request){
        return self::when($request->user_id, function($query) use($request){
            return $query->where('user_id', '=', $request->user_id);
        })->when($request->experience_id, function($query)  use($request){
            return $query->where('experience_id', '=', $request->experience_id);
        })->when($request->rating, function($query)  use($request){
            return $query->where('rating', '=', $request->rating);
        })->when($request->min_rating, function($query)  use($request){
            return $query->where('rating', '>=', $request->min_rating);
        })->when($request->max_rating, function($query)  use($request){
            return $query->where('rating', '<=', $request->max_rating);
        });
    }

    public function experience(){
        return $this->belongsTo('App\Experience');
    }
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
