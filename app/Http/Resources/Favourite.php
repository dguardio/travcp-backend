<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Favourite extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $result["id"] = $this->id;
        $result["user"] = new Plain($this->user);
        $result["experience"] = new Experience($this->experience);
        $result["created_at"] = $this->created_at;
        $result["updated_at"] = $this->updated_at;

        return $result;
    }
}
