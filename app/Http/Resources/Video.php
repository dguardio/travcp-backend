<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Video extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $result = array();
        $result +=  parent::toArray($request);
        $result["video_category"] = new VideoCategory($this->category);
        unset($result["video_category_id"]);
        return $result;
    }
}
