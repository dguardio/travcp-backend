<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function list(Request $request){
        $events = Event::latest()->paginate(20);
        return response()->json($events);
    }
}
