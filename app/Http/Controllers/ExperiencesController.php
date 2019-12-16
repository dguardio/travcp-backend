<?php

namespace App\Http\Controllers;

use App\Upload;
use App\Experience;
use App\MerchantExtra;
use App\ExperienceType;
use App\Traits\Uploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Experience as ExperienceResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\Experiences\ExperiencesStoreRequest;
use App\Http\Requests\Experiences\ExperiencesUpdateRequest;

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
        // dd($request->all());
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
        $experiences = Experience::where('merchant_id', $id)
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

    public function experienceFullyBooked(Request $request, $experience_id)
    {
        // Get number admittable for experience
        $experience = Experience::findOrFail($experience_id);
        $number_admittable = $experience->number_admittable;
        // Get the sum of qty for that experience on the bookings table at start date
        $total_bookings = DB::table('bookings')
            ->where('start_date', 'LIKE', $request->start_date. "%")
            ->where('experience_id', $experience_id)
            ->sum('quantity');
        
        $is_experience_fully_booked = ( ($number_admittable == $total_bookings) || ($total_bookings > $number_admittable) ) ? true : false;
        
        $data = compact('experience', 'total_bookings', 'is_experience_fully_booked');
        // If number admittable and sum value same value, event fully booked, false only if na < sv

        return response()->json($data, 200);
    }

    public function homepage_featured_experiences(Request $request)
    {
        $experience_type_name = $request->experience_type;
        // dd($experience_type_name, $request);
        $experience_type = ExperienceType::whereName($experience_type_name)
            ->firstOrFail();
        // Order by the video recently updated
        $featured_experiences = Experience::whereApproved(1)
            ->where('is_homepage_featured', 1)
            ->where('experiences_type_id', $experience_type->id)
            ->orderBy('updated_at', 'desc')
            ->get();
        return ExperienceResource::collection($featured_experiences);
    }
}