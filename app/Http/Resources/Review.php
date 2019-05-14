<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Review extends Resource
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
        $result["user_name"] = $this->user->first_name." ".$this->user->surname;
        return $result;
    }
}
