<?php

namespace App\Http\Controllers;

use App\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function list(Request $request){
        $restaurants = Restaurant::getBySearch($request);
        return response()->json($restaurants);
    }

    public function show(Request $request, $id){
        $restaurant = Restaurant::findOrFail($id);
        return response()->json(['data' => $restaurant, 'status' => 'success']);
    }
}
