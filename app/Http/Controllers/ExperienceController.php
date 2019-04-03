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
        $experience = Experience::findOrFail($id);
        return response()->json(['data' => $experience, 'status' => 'success']);
    }
}
