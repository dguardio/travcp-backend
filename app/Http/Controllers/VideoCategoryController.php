<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoCategories\VideoCategoriesStoreRequest;
use App\Http\Requests\VideoCategories\VideoCategoriesUpdateRequest;
use App\VideoCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Resources\VideoCategory as VideoCategoryResource;
class VideoCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $limit = $request->has('_limit')? $request->_limit : 20;

        // get all video categories from latest to oldest
        $video_categories = VideoCategory::getBySearch($request)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        // return video category as a resource
        return VideoCategoryResource::collection($video_categories);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param VideoCategoriesStoreRequest $request
     * @return VideoCategoryResource
     */
    public function store(VideoCategoriesStoreRequest $request)
    {
        // return validated data and throw error if there is one
        $validated = $request->validated();

        // create new video category object from validated data
        $video_category = VideoCategory::firstOrNew($validated);

        // save video category if all is well
        if($video_category->save()){
            return new VideoCategoryResource($video_category);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to create video category'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return VideoCategoryResource
     */
    public function show($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single video category
        try{
            $video_category = VideoCategory::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["video category entry not found"];
            return response(['errors'=> $errors], 404);
        }

        //return single video category as a resource
        return new VideoCategoryResource($video_category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param VideoCategoriesUpdateRequest $request
     * @param  int $id
     * @return VideoCategoryResource
     */
    public function update(VideoCategoriesUpdateRequest $request, $id)
    {
        // create video category object
        $video_category =  VideoCategory::findOrFail($id);

        // validate request and return validated data
        $validated = $request->validated();

        //add other video category object properties
        $video_category->update($validated);

        //save video category if transaction goes well
        if($video_category->save()){
            return new VideoCategoryResource($video_category);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to update video category'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return VideoCategoryResource
     */
    public function destroy($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single video category
        try{
            $video_category = VideoCategory::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["video category not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete video category
        if($video_category->delete()){
            return new VideoCategoryResource($video_category);
        }

        $errors = ['unknown error occurred while trying to delete video category'];
        return response(['errors'=> $errors], 500);
    }
}
