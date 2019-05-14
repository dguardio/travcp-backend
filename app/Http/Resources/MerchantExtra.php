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
        $old =  parent::toArray($request);

        // add extra data
        $old["identity_document"] = new Upload($this->upload);

        // remove unnecessary data
        unset($old['upload_id']);

        // return array
        return $old;
    }
}
