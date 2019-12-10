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
        return self::when($request->title, function($query)  use($request){
            return $query->where('title', "LIKE", "%{$request->title}%");
        })->when($request->location, function($query) use($request){
            return $query->where('location', "LIKE", "%{$request->location}%")
                ->orWhere('city', "LIKE", "%{$request->location}%")
                ->orWhere('state', 'LIKE', "%{$request->location}%");
        })->when($request->min_price, function($query) use($request){
            if(!isset($request->currency)){
                return $query->where('naira_price', '>=', $request->min_price);
            }else{
                switch ($request->currency){
                    case "dollars":
                        return $query->where('dollar_price', '>=', $request->min_price);
                        break;
                    case "pounds":
                        return $query->where('pounds_price', '>=', $request->min_price);
                        break;
                    case "naira":
                        return $query->where('naira_price', '>=', $request->min_price);
                        break;
                }
            }
        })->when($request->max_price, function($query) use($request){
            if(!isset($request->currency)){
                return $query->where('naira_price', '<=', $request->max_price);
            }else{
                switch ($request->currency){
                    case "dollars":
                        return $query->where('dollar_price', '<=', $request->max_price);
                        break;
                    case "pounds":
                        return $query->where('pounds_price', '<=', $request->max_price);
                        break;
                    case "naira":
                        return $query->where('naira_price', '<=', $request->max_price);
                        break;
                }
            }
        })->when($request->experiences_type_id, function($query)  use($request){
            return $query->where('experiences_type_id', '=', $request->experiences_type_id);
        })->when($request->rating, function($query)  use($request){
            return $query->where('rating', '>=', $request->rating);
        })->when($request->cities, function($query)  use($request){
            return $query->whereIn('city', $request->cities);
        })->when($request->experience_types, function($query)  use($request){
            $experience_types = trim($request->experience_types, "[,]" );
            $experience_types = explode(",", $experience_types);
            return $query->whereIn('experiences_type_id', $experience_types);
        })->when($request->sort_option, function($query)  use($request){
            $sort_option = $request->sort_option;
            if ($sort_option == "highest_ranked") {
                return $query->orderBy('rating', 'DESC');
            } elseif ($sort_option == "most_recent") {                
                return $query->orderBy('created_at', 'DESC');
            }
        });
    }

    public function menus(){
        return $this->hasMany(FoodMenu::class, 'restaurant_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'merchant_id');
    }

    public function reviews(){
        return $this->hasMany('App\Review');
    }

    public function bookings(){
        return $this->hasMany('App\Booking');
    }

    public function uploads(){
        return $this->hasMany("App\Upload");
    }

    public function experience_type(){
        return $this->belongsTo("App\ExperienceType", 'experiences_type_id');
    }

    public function favourites(){
        return $this->hasMany(Favourite::class);
    }
}
