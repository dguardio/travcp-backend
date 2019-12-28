<?php

namespace App\Http\Controllers;

use App\User;
use App\Medal;
use App\Review;
use App\Upload;
use App\Experience;
use App\Traits\Uploads;
use Illuminate\Http\Request;
use App\Http\Resources\ReviewCollection;
use App\Http\Resources\Review as ReviewResource;
use App\Http\Requests\Reviews\ReviewsStoreRequest;
use App\Http\Requests\Reviews\ReviewsUpdateRequest;
use App\Notification;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReviewsController extends Controller
{
    use Uploads;

    /**
     * Display a listing of the all reviews in the db.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $limit = $request->has('_limit')? $request->_limit : 20;

        // get reviews
        $reviews = Review::getBySearch($request)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        // return collection of reviews as a resource
        return ReviewResource::collection($reviews);
    }

    /**
     * store or update a review.
     * @param ReviewsStoreRequest $request
     * @return ReviewResource
     */
    public function storeOrUpdate(ReviewsStoreRequest $request){
        // get validated request data, throw error if applicable
        $validated = $request->validated();

        // get old experience
        $experience = Experience::find($validated['experience_id']);

        // get old experience average rating for calculation of new rating
        $old_avg = $experience->rating;

        // get old security average
        $old_security_avg = $experience->security_rating;


        try{
            // check if review has been stored before and update it
            $review = Review::where('user_id', $validated['user_id'])
                ->where('experience_id', $validated['experience_id'])
                ->firstOrFail();

            $extras = ["review_id" => $review->id];

            // store file and get file upload id
            if($review->upload_id != -1){
                $review->upload_id = $this->updateFile($request, $review->upload_id, 'video', $extras);
            }else{
                $review->upload_id = $this->storeFile($request, 'video', $extras);
            }

            unset($validated['video']);

            // get old review rating
            $old_rating = $review->rating;

            // get old security rating
            $old_security_rating = $review->security_rating;


            // calculate and update experience rating
            $rating = $validated["rating"];
            if(($experience->rating_count - 1) == 0){
                $pre_rating = 0;
            }else{
                $pre_rating =  ( ($old_avg*($experience->rating_count)) - $old_rating ) / ($experience->rating_count - 1);
            }
            $new_rating = (($pre_rating*($experience->rating_count - 1)) + $rating) / $experience->rating_count;
            $experience->rating = $new_rating;

            if(isset($validated["security_rating"]) && !is_null($validated["security_rating"])){
                // calculate and update security rating
                $security_rating = $validated["security_rating"];
                if(($experience->security_rating_count - 1) == 0){
                    $pre_security_rating = 0;
                }else{
                    $pre_security_rating = (($old_security_avg*($experience->security_rating_count - 1)) - $old_security_rating) / ($experience->security_rating_count - 1) ;
                }
                $experience->security_rating = (($pre_security_rating*($experience->security_rating_count - 1)) + $security_rating) / $experience->security_rating_count;
            }

            // update review
            $review->update($validated);

        }catch (ModelNotFoundException $e) {
            $review = new Review();
            $review->save();

            $extras = ["review_id" => $review->id];

            $upload_id = $this->storeFile($request, 'video', $extras);
            unset($validated['video']);

            // create new review from array data
            $review->fill($validated);
            $review->save();

            // Handle medal awarding based on reviews
            $this->handleMedalAwarding($validated['user_id']);

            // add video file
            $review->upload_id = $upload_id;
            $review->save();

            // add experience rating
            $experience->rating = (($experience->rating*$experience->rating_count) + $review->rating) / ++$experience->rating_count;

            // add experience security rating
            if (isset($validated["security_rating"]) && !is_null($validated["security_rating"])) {
                $experience->security_rating = (($experience->security_rating*$experience->security_rating_count) + $review->security_rating) / ++$experience->security_rating_count;
            }
        }
        // just to make the return json return an int not a string
        $review->rating += 0;

        // update experience
        $experience->save();

        // return review as a resource;
        return new ReviewResource($review);
    }

    private function handleMedalAwarding($user_id)
    {
        // Get number of user reviews
        $user_reviews = Review::where('user_id', $user_id)->count();
        // Get medals
        $medals = Medal::all();
        $user = User::find($user_id);

        foreach ($medals as $medal) {
            if ($user_reviews >= $medal->review_threshold) {
                // update user medal_id
                $user->medal_id = $medal->id;
            }
        }

        if ($user->isDirty('medal_id')) {
            // Get medal name
            $medal_name = Medal::where('id', $user->medal_id)->first()->name;
            // Add to notification and send email
            $notification_body = "Congratulations! You just received a medal! You've become a " . $medal_name . "!";
            Notification::create(
                compact('user_id', 'notification_body')
            );          
        }
    }

    /**
     * Store a newly created review in storage.
     *
     * @param ReviewsStoreRequest $request
     * @return ReviewResource
     */
    public function store(ReviewsStoreRequest $request)
    {
        // validate request and return validated data
        $validated = $request->validated();

        // quickly upload
        $validated["upload_id"] = $this->storeFile($request, 'video');

        unset($validated["video"]);

        // create review object and add other review object properties
        $review =  new Review($validated);

        // save review if transaction goes well
        if($review->save()){
            if($review->upload_id !== -1){
                $upload = Upload::findOrFail($review->upload_id);
                $upload->review_id = $review->id;
                $upload->save();
            }
            return new ReviewResource($review);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to create review'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Display the specified review using its id.
     *
     * @param  int  $id
     * @return ReviewResource
     */
    public function show($id)
    {
        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single review
        try{
            $review = Review::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["review not found"];
            return response(['errors'=> $errors], 404);
        }

        // return single review as a resource
        return new ReviewResource($review);
    }

    /**
     * Update the specified review in storage.
     *
     * @param ReviewsUpdateRequest $request
     * @param  int $id
     * @return ReviewResource
     */
    public function update(ReviewsUpdateRequest $request, $id)
    {
        // create review object
        $review =  Review::findOrFail($id);

        // validate request and return validated data
        $validated = $request->validated();

        $extras = ["review_id" => $review->id];

        // store file and get file upload id
        if($review->upload_id != -1){
            $review->upload_id = $this->updateFile($request, $review->upload_id, 'video', $extras);
        }else{
            $review->upload_id = $this->storeFile($request, 'video', $extras);
        }

        unset($validated['video']);


        // add other review object properties
        $review->update($validated);

        // save review if transaction goes well
        if($review->save()){
            return new ReviewResource($review);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to update review'];
        return response(['errors'=> $errors], 500);

    }

    /**
     * get all reviews from all the experiences of a particular merchant using id
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getReviewsByMerchantId(Request $request, $id){
        $limit = $request->has('_limit')? $request->_limit : 20;

        // get reviews based on user id
        $reviews = Review::getBySearch($request)
            ->where('user_id', $id)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        // return reviews as collection
        return ReviewResource::collection($reviews);
    }

    /**
     * get al reviews from an experience
     * @param Request $request
     * @param $id
     * @return ReviewCollection
     */
    public function getReviewsByExperienceId(Request $request, $id){
        $limit = $request->has('_limit')? $request->_limit : 20;

        // all experience reviews
        $reviews = Review::getBySearch($request)
            ->where('experience_id', $id)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        // as a result
        return new ReviewCollection($reviews);
    }

    /**
     *  get experience reviews by rating id
     * @param Request $request
     * @param $experience_id
     * @param $rating
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getExperienceReviewByRating(Request $request, $experience_id, $rating){
        $limit = $request->has('_limit')? $request->_limit : 20;

        // get reviews
        $reviews = Review::getBySearch($request)
            ->where('experience_id', $experience_id)
            ->where('rating', $rating)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        // return reviews as collection
        return ReviewResource::collection($reviews);
    }

    /**
     * Remove the specified review from storage.
     *
     * @param  int  $id
     * @return ReviewResource
     */
    public function destroy($id)
    {
        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single review
        try{
            $review = Review::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["review not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete reviews
        if($review->delete()){

            return new ReviewResource($review);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to delete review'];
        return response(['errors'=> $errors], 500);
    }
}
