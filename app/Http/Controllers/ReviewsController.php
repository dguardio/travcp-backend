<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reviews\ReviewsStoreRequest;
use App\Http\Requests\Reviews\ReviewsUpdateRequest;
use App\Http\Resources\ReviewCollection;
use App\Review;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\Review as ReviewResource;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    /**
     * Display a listing of the all reviews in the db.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        // get reviews
        $reviews = Review::getBySearch($request)
            ->orderBy('id', 'DESC')
            ->paginate(20);

        // return collection of reviews as a resource
        return ReviewResource::collection($reviews);
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

        // create review object and add other review object properties
        $review =  new Review($validated);

        // save review if transaction goes well
        if($review->save()){
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
        // get reviews based on user id
        $reviews = Review::getBySearch($request)
            ->where('user_id', $id)
            ->orderBy('id', 'DESC')
            ->paginate(20);

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
        // all experience reviews
        $reviews = Review::getBySearch($request)
            ->where('experience_id', $id)
            ->orderBy('id', 'DESC')
            ->paginate(20);

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
        // get reviews
        $reviews = Review::getBySearch($request)
            ->where('experience_id', $experience_id)
            ->where('rating', $rating)
            ->orderBy('id', 'DESC')
            ->paginate(10);

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
