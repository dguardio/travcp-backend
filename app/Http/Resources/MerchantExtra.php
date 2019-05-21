<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class MerchantExtra extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // get parent data
        $result =  parent::toArray($request);

        // add extra data
        $result["identity_document"] = new Upload($this->upload);

        $result["user_data"] = new User($this->user);
        // remove unnecessary data
        unset($result['upload_id']);

        // return array
        return $result;
    }
}
