<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExperienceType extends Model
{
    protected $table = "experiences_types";

    protected $guarded = [];

    public function experiences(){
        return $this->hasMany("App\Experiences", 'experiences_type_id');
    }

}
