<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = "reviews";

    protected $guarded = [];

    public function experience(){
        return $this->belongsTo('App\Experience');
    }
}
