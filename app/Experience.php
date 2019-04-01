<?php

namespace App;

use Illuminate\Http\Request;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasSlug;

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
        })->paginate();
    }
}
