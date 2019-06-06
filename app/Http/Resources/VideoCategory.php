<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class VideoCategory extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $result = parent::toArray($request);
        unset($result["created_at"]);
        unset($result["updated_at"]);

        return $result;
    }
}
