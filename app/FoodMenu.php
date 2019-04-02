<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FoodMenu extends Model
{
    protected $guarded = [];

    protected $table = "food_menu";

    public function restaurant(){
        return $this->belongsTo(Restaurant::class);
    }
}
