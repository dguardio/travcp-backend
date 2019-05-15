<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Cart extends Resource
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
        $result["items"] = CartItem::collection($this->items);
        return $result;
    }
}
