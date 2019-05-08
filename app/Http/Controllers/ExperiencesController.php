<?php

namespace App\Http\Controllers;

use App\Http\Requests\Experiences\ExperiencesStoreRequest;
use App\Http\Requests\Experiences\ExperiencesUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Experience;
use App\Http\Resources\Experience as ExperienceResource;
use Illuminate\Http\Request;

class ExperiencesController extends Controller
{
    /**
     * Display a listing of all experiences, searches with pagination.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        // get experiences
        $experiences = Experience::getBySearch($request)->appends($request->query())->paginate(10);

        // return collection of experiences as a resource
        return ExperienceResource::collection($experiences);
    }

    /**
     * Store a newly created experience in storage.
     *
     * @param ExperiencesStoreRequest $request
     * @return ExperienceResource
     */
    public function store(ExperiencesStoreRequest $request)
    {
        // validate request and return validated data
        $validated = $request->validated();

        // create new experience object and add other user payments object properties
        $experience =  new Experience($validated);

        // save experience if transaction goes well
        if($experience->save()){
            return new ExperienceResource($experience);
        }

        return new ExperienceResource(null);
    }

    /**
     * Display the specified experience.
     *
     * @param  int  $id
     * @return ExperienceResource
     */
    public function show($id)
    {
        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single experience
        try{
            $experience = Experience::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["experience entry not found"];
            return response(['errors'=> $errors], 404);
        }

        // add reviews to the shii
        // return single experience as a resource
        return new ExperienceResource($experience);
    }

    /**
     * get specified experience by merchant id
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getExperienceByMerchantId($id, Request $request){
        // get experiences by merchant id
        $experiences = Experience::getBySearch($request)
            ->where('merchant_id', $id)
            ->orderBy('id', 'DESC')
            ->paginate(10)
            ->appends($request->query());

        // return collection of experiences as a resource
        return ExperienceResource::collection($experiences);
    }

    /**
     * get all experiences by specified type id
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getExperiencesByTypesId($id, Request $request){
        // get experiences by experience types id
        $experiences = Experience::getBySearch($request)
            ->where('experiences_type_id', $id)
            ->orderBy('id', 'DESC')
            ->paginate(10)
            ->appends($request->query());

        // return collection of experiences as a resource
        return ExperienceResource::collection($experiences);
    }

    /**
     * Update the specified experience in storage.
     *
     * @param ExperiencesUpdateRequest $request
     * @param  int $id
     * @return ExperienceResource
     */
    public function update(ExperiencesUpdateRequest $request, $id)
    {
        // create experience object
        $experience =  Experience::findOrFail($id);

        // validate request and return validated data
        $validated = $request->validated();

        // add other experience object properties
        $experience->update($validated);

        // save experience if transaction goes well
        if($experience->save()){
            return new ExperienceResource($experience);
        }

        return new ExperienceResource(null);
    }

    /**
     * Remove the specified experience from storage.
     *
     * @param  int  $id
     * @return ExperienceResource
     */
    public function destroy($id)
    {
        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single experience
        try{
            $experience = Experience::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["experience entry not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete experience entry
        if($experience->delete()){
            return new ExperienceResource($experience);
        }

        return new ExperienceResource(null);
    }
}
