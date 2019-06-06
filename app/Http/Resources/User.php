<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class User extends Resource
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

        $result['signed_in'] = (boolean)$result['signed_in'];
        $result['verified'] = (boolean)$result['verified'];

        $result["profile_image"] = new Upload($this->upload);
        $result["role"] = $this->role->name;

        $result["written_reviews"] = $this->reviews->count();

        $medals = \App\Medal::orderBy('review_threshold', 'ASC')->all();

        if($medals->count() > 0){
            foreach ($medals as $medal){
                if($result["written_reviews"] < $medal->review_threshold) break;
                $result["user_medal"] = $medal->name;
            }
        }

        unset($result["upload_id"]);
        unset($result["role_id"]);
        return $result;
    }
}
