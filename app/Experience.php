<?php

namespace App;

use Illuminate\Http\Request;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use willvincent\Rateable\Rateable;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasSlug, Rateable;

    protected $guarded = [];
    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public static function getBySearch(Request $request){
        return self::when($request->location, function($query) use($request){
            return $query->where('city', "LIKE", "%{$request->location}%")->orWhere('state', 'LIKE', "%{$request->location}%");
        })->when($request->min_price, function($query) use($request){
            return $query->where('naira_price', '>=', $request->min_price);
        })->when($request->max_price, function($query)  use($request){
            return $query->where('naira_price', '<=', $request->max_price);
        })->when($request->type, function($query)  use($request){
            return $query->where('type', '=', $request->type);
        })->when($request->rating, function($query)  use($request){
            return $query->where('rating', '>=', $request->rating);
        })->when($request->cities, function($query)  use($request){
            return $query->whereIn('city', $request->cities);
        })->paginate(10);
    }

    public function menus(){
        return $this->hasMany(FoodMenu::class);
    }

    public function user(){
        return $this->belongsTo('App\User', 'merchant_id');
    }

    public function reviews(){
        return $this->hasMany('App\Review');
    }
}
