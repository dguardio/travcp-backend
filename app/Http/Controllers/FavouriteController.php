<?php

namespace App\Http\Controllers;

use App\Favourite;
use App\Http\Requests\Favourite\FavouriteStoreRequest;
use App\Http\Requests\Favourite\FavouriteUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Resources\Favourite as FavouriteResource;

class FavouriteController extends Controller
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

        // get all favourites from latest to oldest
        $favourites = Favourite::getBySearch($request)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        // return favourites as a resource collection
        return FavouriteResource::collection($favourites);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FavouriteStoreRequest $request
     * @return FavouriteResource
     */
    public function store(FavouriteStoreRequest $request)
    {
        // return validated data and throw error if there is one
        $validated = $request->validated();

        // create new favourite object from validated data
        $favourite = new Favourite($validated);

        // save favourite if all is well
        if($favourite->save()){
            return new FavouriteResource($favourite);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to create favourite'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return FavouriteResource
     */
    public function show($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single favourite
        try{
            $favourite = Favourite::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["favourite not found"];
            return response(['errors'=> $errors], 404);
        }

        //return single favourite as a resource
        return new FavouriteResource($favourite);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FavouriteUpdateRequest $request
     * @param  int $id
     * @return FavouriteResource
     */
    public function update(FavouriteUpdateRequest $request, $id)
    {
        // create favourite object
        $favourite =  Favourite::findOrFail($id);

        // validate request and return validated data
        $validated = $request->validated();

        //add other favourite object properties
        $favourite->update($validated);

        //save favourite if transaction goes well
        if($favourite->save()){
            return new FavouriteResource($favourite);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to update favourite'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return FavouriteResource
     */
    public function destroy($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single favourite
        try{
            $favourite = Favourite::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["favourite entry not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete favourite
        if($favourite->delete()){
            return new FavouriteResource($favourite);
        }

        $errors = ['unknown error occurred while trying to delete favourite'];
        return response(['errors'=> $errors], 500);
    }
}
