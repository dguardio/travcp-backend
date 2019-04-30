<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reviews\ReviewsStoreRequest;
use App\Http\Requests\Reviews\ReviewsUpdateRequest;
use App\Review;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\Review as ReviewResource;

class ReviewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        // get reviews
        $reviews = Review::orderBy('id', 'DESC')->paginate(10);

        // return collection of reviews as a resource
        return ReviewResource::collection($reviews);
    }

    /**
     * Store a newly created resource in storage.
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

        return new ReviewResource(null);
    }

    /**
     * Display the specified resource.
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
     * Update the specified resource in storage.
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

        return new ReviewResource(null);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getReviewsByMerchantId($id){
        // get reviews based on user id
        $reviews = User::findOrFail($id)->experiences()->reviews()->paginate(10);

        // return reviews as collection
        return ReviewResource::collection($reviews);
    }

    /**
     * Remove the specified resource from storage.
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

        return new ReviewResource(null);
    }
}
