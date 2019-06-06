<?php

namespace App\Http\Controllers;

use App\ExperienceType;
use App\Http\Requests\ExperienceTypes\ExperienceTypesStoreRequest;
use App\Http\Requests\ExperienceTypes\ExperienceTypesUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\ExperienceType as ExperienceTypeResource;
use Illuminate\Http\Request;

class ExperienceTypesController extends Controller
{
    /**
     * Display a listing of all experience types.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $limit = $request->has('_limit')? $request->_limit : 20;

        // get all experiences types
        $experiences_types = ExperienceType::orderBy('id', 'DESC')->paginate($limit);

        // return experience types as a collection
        return ExperienceTypeResource::collection($experiences_types);
    }

    /**
     * @param $name
     * @return ExperienceTypeResource
     */
    public function getExperienceTypeByName($name)
    {
        // get experiences types by name
        $experiences_type = ExperienceType::where('name', $name)->orderBy('id', 'DESC')->firstOrFail();

        // return experience types as a collection
        return new ExperienceTypeResource($experiences_type);
    }


    /**
     * Store a newly created experience type in storage.
     *
     * @param ExperienceTypesStoreRequest $request
     * @return ExperienceTypeResource
     */
    public function store(ExperienceTypesStoreRequest $request)
    {
        // validate request and return validated data
        $validated = $request->validated();

        // create experience type object and add other notification object properties
        $experience_type =  ExperienceType::firstOrNew($validated);

        // save experience type if transaction goes well
        if($experience_type->save()){
            return new ExperienceTypeResource($experience_type);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to create experience type'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Display the specified experience type by id.
     *
     * @param  int  $id
     * @return ExperienceTypeResource
     */
    public function show($id)
    {
        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single experience type
        try{
            $experience_type = ExperienceType::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["experience type not found"];
            return response(['errors'=> $errors], 404);
        }

        // return single experience type as a resource
        return new ExperienceTypeResource($experience_type);
    }

    /**
     * Update the specified experence type in the storage.
     *
     * @param ExperienceTypesUpdateRequest $request
     * @param  int $id
     * @return ExperienceTypeResource
     */
    public function update(ExperienceTypesUpdateRequest $request, $id)
    {
        // create experience types object
        $experience_type =  ExperienceType::findOrFail($id);

        // validate request and return validated data
        $validated = $request->validated();

        //add other experience type object properties
        $experience_type->update($validated);

        //save experience type if transaction goes well
        if($experience_type->save()){
            return new ExperienceTypeResource($experience_type);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to update experience type'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Remove the specified experience type from storage.
     *
     * @param  int  $id
     * @return ExperienceTypeResource
     */
    public function destroy($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single experience type
        try{
            $experience_type = ExperienceType::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["experience type entry not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete experience type
        if($experience_type->delete()){
            return new ExperienceTypeResource($experience_type);
        }

        $errors = ['unknown error occurred while trying to delete experience type'];
        return response(['errors'=> $errors], 500);
    }
}
