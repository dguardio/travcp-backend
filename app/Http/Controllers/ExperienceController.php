<?php

namespace App\Http\Controllers;

use App\Experience;
use Illuminate\Http\Request;    

class ExperienceController extends Controller
{
    public function list(Request $request){
        $experiences = Experience::getBySearch($request)->appends($request->query());
        return response()->json($experiences);
    }

    public function show(Request $request, $id){
        // $data = ['id','bookable_ty  pe_id','title','slug','description','merchant_id',
        // 'location','city','state','drink_types','dining_options','has_outdoor_sitting','opening_and_closing_hours','extra_perks','cancellation_policy'];
        $experience = Experience::where('type','restaurant')->findOrFail($id);
        return response()->json(['data' => $experience, 'status' => 'success']);
    }
}
