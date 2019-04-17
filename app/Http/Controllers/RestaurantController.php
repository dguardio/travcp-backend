<?php

namespace App\Http\Controllers;

use App\Restaurant;
use App\Experience;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function list(Request $request){
        $restaurants = Experience::getBySearch($request)->where('type','restaurant');
        return response()->json($restaurants);
    }

    public function show(Request $request, $id){
        $data = ['id','bookable_ty  pe_id','title','slug','description','merchant_id',
                'location','city','state','drink_types','dining_options','has_outdoor_sitting','opening_and_closing_hours','extra_perks','cancellation_policy'];
        $restaurant = Experience::select($data)->where('type','restaurant')->findOrFail($id);
        return response()->json(['data' => $restaurant, 'status' => 'success']);
    }
}
