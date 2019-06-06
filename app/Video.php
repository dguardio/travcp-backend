<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Video extends Model
{
    protected $table = "videos";
    protected $guarded = [];

    /**
     * listed with events listeners for each time a model is created or saved.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            if(!isset($model->user_id)){
                $model->user_id = auth()->user()->id;
            }
        });

        static::updating (function($model)
        {
            if(!isset($model->user_id)){
                $model->user_id = auth()->user()->id;
            }
        });
    }

    /**
     * get all videos based on these params
     * @param Request $request
     * @return mixed
     */
    public static function getBySearch(Request $request){
        return self::when($request->title, function($query) use($request){
            return $query->where('title', "LIKE", "%{$request->title}%");
        })->when($request->description, function($query) use($request){
            return $query->where('description', "LIKE", "%{$request->description}%");
        })->when($request->user_id, function($query) use($request){
            return $query->where('user_id', "=", $request->user_id);
        })->when($request->video_category_id, function($query)  use($request){
            return $query->where('video_category_id', '=', $request->video_category_id);
        });
    }

    /**
     * get categories relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(){
        return $this->belongsTo(VideoCategory::class, "video_category_id");
    }
}
