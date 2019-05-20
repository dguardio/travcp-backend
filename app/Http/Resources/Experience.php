<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Experience extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $result =  parent::toArray($request);

        // manipulate date
        if($this->start_date && $this->end_date){
            $num_seconds_between = strtotime($this->end_date) - strtotime($this->start_date)  ;
            $num_days_between = $num_seconds_between / 60 / 60 / 24;
            $result["duration"] = $num_days_between;
        }

        $result["experience_type"] = $this->experience_type->name;
        $result["reviews"] = Review::collection($this->reviews->take(5));
        $result["images"] = Upload::collection($this->uploads);

        return $result;
    }
}
