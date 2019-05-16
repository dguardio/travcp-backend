<?php

namespace App\Http\Controllers;

use App\FoodMenu;
use App\Restaurant;
use App\Http\Resources\FoodMenu as FoodMenuResource;
class FoodMenuController extends Controller
{
    /**
     * get all food menus of a particular restaurant
     * @param $restaurantId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list($restaurantId){
        $restaurant = Restaurant::findOrFail($restaurantId);
        $menus = FoodMenu::where('restaurant_id', $restaurantId)->latest()->paginate(20);
        return FoodMenuResource::collection($menus);
    }
}
