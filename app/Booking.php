<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = "bookings";
    protected $guarded = [];

    public function experience(){
        return $this->belongsTo('App\Experience');
    }
}
