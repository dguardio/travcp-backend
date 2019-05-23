<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReviewCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);

        $result =  [
            "data" => $this->collection
        ];

        if(!is_null($this->collection->first())){
            $one = $this->collection->first();
            $result["rating_info"] = [
                "5" => \App\Review::where('experience_id', $one->experience_id)->where('rating', 5)->count(),
                "4" => \App\Review::where('experience_id', $one->experience_id)->where('rating', 4)->count(),
                "3" => \App\Review::where('experience_id', $one->experience_id)->where('rating', 3)->count(),
                "2" => \App\Review::where('experience_id', $one->experience_id)->where('rating', 2)->count(),
                "1" => \App\Review::where('experience_id', $one->experience_id)->where('rating', 1)->count(),
            ];
        }
        return $result;
    }
}
