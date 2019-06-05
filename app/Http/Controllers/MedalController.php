<?php

namespace App\Http\Controllers;

use App\Http\Requests\Medal\MedalStoreRequest;
use App\Http\Requests\Medal\MedalUpdateRequest;
use App\Medal;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Resources\Medal as MedalResource;
class MedalController extends Controller
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

        // get all medals from latest to oldest
        $medals = Medal::getBySearch($request)
            ->orderBy('review_threshold', 'ASC')
            ->paginate($limit);

        // return medals as a resource
        return MedalResource::collection($medals);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MedalStoreRequest $request
     * @return MedalResource
     */
    public function store(MedalStoreRequest $request)
    {
        // return validated data and throw error if there is one
        $validated = $request->validated();

        // create new medal object from validated data
        $medals = new Medal($validated);

        // save medal if all is well
        if($medals->save()){
            return new MedalResource($medals);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to create medal'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return MedalResource
     */
    public function show($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single medal
        try{
            $medal = Medal::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["medal not found"];
            return response(['errors'=> $errors], 404);
        }

        //return single medal as a resource
        return new MedalResource($medal);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param MedalUpdateRequest $request
     * @param  int $id
     * @return MedalResource
     */
    public function update(MedalUpdateRequest $request, $id)
    {
        // create medal object
        $medal =  Medal::findOrFail($id);

        // validate request and return validated data
        $validated = $request->validated();

        //add other medal object properties
        $medal->update($validated);

        //save medal if transaction goes well
        if($medal->save()){
            return new MedalResource($medal);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to update medal'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return MedalResource
     */
    public function destroy($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single medal
        try{
            $medal = Medal::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["medal entry not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete medal
        if($medal->delete()){
            return new MedalResource($medal);
        }

        $errors = ['unknown error occurred while trying to delete medal'];
        return response(['errors'=> $errors], 500);
    }
}
