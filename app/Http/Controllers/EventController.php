<?php

namespace App\Http\Controllers;

use App\Experience;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function list(Request $request){
        $data = ['id','bookable_type_id','title','slug',
        'merchant_id','about_merchant','location','description'
        ,'contact_email'];
        $events = Experience::select($data)->where('type','events')->latest()->paginate(20);
        return response()->json($events);
    }
}
