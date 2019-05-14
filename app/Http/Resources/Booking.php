<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Booking extends Resource
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
        $result['experience'] = new Experience($this->experience);
        return $result;
    }
}
