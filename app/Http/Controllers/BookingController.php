<?php

namespace App\Http\Controllers;

use App\Event;
use App\Booking;
use Carbon\Carbon;
use App\Experience;
use App\BookableType;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function bookExperience(Request $request, $id){
        $this->validate($request, [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date'
        ]);
        $experienceId = $id;
        $bookableType = BookableType::whereName('experience')->firstOrFail();
        $experience = Experience::findOrFail($id);
        $booking = new Booking();
        $booking->bookable_type_id = $bookableType->id;
        $booking->bookable_type_name = $bookableType->name;
        $booking->bookable_id = $id;
        $booking->user_id = auth()->id();
        $booking->bookable_name = $experience->title;
        $booking->merchant_id = $experience->merchant_id;
        $booking->price = $experience->naira_price;
        $booking->currency = "Naira";
        $booking->start_date = $request->start_date;
        $booking->end_date = $request->end_date;
        $booking->save();

        return response()->json(['status' => 'success', 'message' => "Booking made successfully", 'data' => $booking]);
    }

    public function bookEvent(Request $request, $id){
        $eventId = $id;
        $bookableType = BookableType::whereName('event')->firstOrFail();
        $event = Event::findOrFail($id);
        $booking = new Booking();
        $booking->bookable_type_id = $bookableType->id;
        $booking->bookable_type_name = $bookableType->name;
        $booking->bookable_id = $id;
        $booking->user_id = auth()->id();
        $booking->bookable_name = $event->title;
        $booking->merchant_id = $event->merchant_id;
        $booking->save();
        return response()->json(['status' => 'success', 'message' => "Booking made successfully", 'data' => $booking]);
    }
}
