<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FoodMenu extends Model
{
    protected $guarded = [];

    protected $table = "food_menu";

    public function restaurant(){
        return $this->belongsTo(Experience::class, 'restaurant_id');
    }

    public function category(){
        return $this->belongsTo(FoodClassification::class, 'category_id');
    }
}
