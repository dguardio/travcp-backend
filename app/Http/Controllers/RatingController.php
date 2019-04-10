<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use willvincent\Rateable\Rating;

class RatingController extends Controller
{
    public function getRatings(Request $request, $userId){
        $user = User::findOrFail($userId);
        $ratings = $user->ratings()->with('user:id,name,email')->paginate(20);
        $total = $user->ratings()->count();
        return response()->json(['ratings' => $ratings, 'average' => \number_format($user->averageRating), 'total' => $total]);
    }

    public function store(Request $request, $userId){
        $rating = new Rating;
        $rating->rating = $request->rating;
        $rating->user_id = auth()->id();
        $rating->review = $request->review;
        if (!Rating::where('user_id', auth()->id())->where('rateable_id', $userId)->exists()){
            User::find($userId)->ratings()->save($rating);
        }
        else{
            User::find($userId)->ratings()->where('user_id', auth()->id())->update(["rating" => $request->rating, "review" => $rating->review]);
        }
        

        return response()->json(['message' => "Your rating has been submitted"]);
    }
}
