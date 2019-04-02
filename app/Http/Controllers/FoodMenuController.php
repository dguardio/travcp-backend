<?php

namespace App\Http\Controllers;

use App\FoodMenu;
use App\Restaurant;
use Illuminate\Http\Request;

class FoodMenuController extends Controller
{
    public function list(Request $request, $restaurantId){
        $restaurant = Restaurant::findOrFail($restaurantId);
        $menus = FoodMenu::where('restaurant_id', $restaurantId)->latest()->paginate(20);
        return response()->json($menus);
    }
}
