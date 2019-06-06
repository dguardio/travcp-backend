<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class VideoCategory extends Model
{
    protected $table = "video_categories";
    protected $guarded = [];

    /**
     * get all video categories based on these params
     * @param Request $request
     * @return mixed
     */
    public static function getBySearch(Request $request){
        return self::when($request->name, function($query) use($request){
            return $query->where('name', "LIKE", "%{$request->name}%");
        });
    }

    /**get videos relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videos(){
        return $this->hasMany(Video::class, "video_category_id");
    }
}
