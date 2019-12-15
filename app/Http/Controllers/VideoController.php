<?php

namespace App\Http\Controllers;

use App\Http\Requests\Videos\VideosStoreRequest;
use App\Http\Requests\Videos\VideosUpdateRequest;
use App\Video;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Resources\Video as VideoResource;
class VideoController extends Controller
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

        // get all videos from latest to oldest
        $videos = Video::getBySearch($request)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        // return videos as a resource
        return VideoResource::collection($videos);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param VideosStoreRequest $request
     * @return VideoResource
     */
    public function store(VideosStoreRequest $request)
    {
        // return validated data and throw error if there is one
        $validated = $request->validated();

        // create new video object from validated data
        $video = new Video($validated);

        // save video if all is well
        if($video->save()){
            return new VideoResource($video);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to create video entry'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return VideoResource
     */
    public function show($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single video
        try{
            $video = Video::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["video entry not found"];
            return response(['errors'=> $errors], 404);
        }

        //return single video as a resource
        return new VideoResource($video);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param VideosUpdateRequest $request
     * @param  int $id
     * @return VideoResource
     */
    public function update(VideosUpdateRequest $request, $id)
    {
        // create video object
        $video =  Video::findOrFail($id);

        // validate request and return validated data
        $validated = $request->validated();

        //add other video object properties
        $video->update($validated);

        //save video if transaction goes well
        if($video->save()){
            return new VideoResource($video);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to update video entry'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return VideoResource
     */
    public function destroy($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single video
        try{
            $video = Video::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["video entry not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete video
        if($video->delete()){
            return new VideoResource($video);
        }

        $errors = ['unknown error occurred while trying to delete video entry'];
        return response(['errors'=> $errors], 500);
    }

    public function homepage_featured_videos()
    {
        $featured_vids = Video::with('video_category')
            ->where('is_homepage_featured', 1)
            ->orderBy('updated_at', 'DESC')
            ->get();
        return response()->json($featured_vids, 200);
    }
}
