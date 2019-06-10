<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class FullUser extends Resource
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
        $referred = Plain::collection($this->referred);

        return $result;
    }
}
