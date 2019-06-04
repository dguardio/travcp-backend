<?php

namespace App\Http\Controllers;

use App\Http\Requests\Experiences\ExperiencesStoreRequest;
use App\Http\Requests\Experiences\ExperiencesUpdateRequest;
use App\MerchantExtra;
use App\Traits\Uploads;
use App\Upload;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Experience;
use App\Http\Resources\Experience as ExperienceResource;
use Illuminate\Http\Request;

class ExperiencesController extends Controller
{
    use Uploads;
    /**
     * Display a listing of all experiences, searches with pagination.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $limit = $request->has('_limit')? $request->_limit : 20;

        // get experiences
        $experiences = Experience::getBySearch($request)
            ->orderBy('id', 'DESC')
            ->paginate($limit)
            ->appends($request->query());

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

        // store file and get filename
        if(app('request')->exists('images')){
            $uploads_ids = $this->multipleImagesUpload($request, 'images');
        }

        unset($validated['images']);

        // create new experience object and add other user payments object properties
        $experience =  new Experience;
        $experience->fill($validated);

//        // try to get merchant extra of the user and add its id to experiences
//        try{
//            $merchant_extras = MerchantExtra::where("user_id", $experience->merchant_id)->firstOrFail();
//            $experience->merchant_extra_id = $merchant_extras->id;
//        }catch (ModelNotFoundException $e){
//            $errors = ["merchant entry for user not found"];
//            return response(['errors'=> $errors], 404);
//        }

        // save experience if transaction goes well
        if($experience->save()){
            if (isset($uploads_ids)) {
                foreach($uploads_ids as $upload_id){
                    if($upload_id !== -1){
                        $upload = Upload::findOrFail($upload_id);
                        $upload->experience_id = $experience->id;
                        $upload->save();
                    }
                }
            }
            return new ExperienceResource(Experience::find($experience->id));
        }

        $errors = ["error while creating experience"];
        return response(['errors'=> $errors], 500);
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
        $limit = $request->has('_limit')? $request->_limit : 20;

        // get experiences by merchant id
        $experiences = Experience::getBySearch($request)
            ->where('merchant_id', $id)
            ->orderBy('id', 'DESC')
            ->paginate($limit)
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
        $limit = $request->has('_limit')? $request->_limit : 20;

        // get experiences by experience types id
        $experiences = Experience::getBySearch($request)
            ->where('experiences_type_id', $id)
            ->orderBy('id', 'DESC')
            ->paginate($limit)
            ->appends($request->query());

        // return collection of experiences as a resource
        return ExperienceResource::collection($experiences);
    }

    /**
     *  get random experiences
     * @param Request $request
     * @param $limit
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getRandom(Request $request, $limit){
        // get 5 random experiences
        $experience = Experience::getBySearch($request)
            ->inRandomOrder()->take($limit || 20)->get();

        // return as experience resource
        return ExperienceResource::collection($experience);
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

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to update experience'];
        return response(['errors'=> $errors], 500);
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