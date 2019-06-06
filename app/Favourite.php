<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Favourite extends Model
{
    protected $table = 'favourites';

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function experience(){
        return $this->belongsTo(Experience::class);
    }

    public static function getBySearch(Request $request){
        return self::when($request->user_id, function($query)  use($request){
            return $query->where('user_id', '=', $request->user_id);
        })->when($request->experience_id, function($query)  use($request){
            return $query->where('experience_id', '=', $request->experience_id);
        });
    }
}
