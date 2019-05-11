<?php

namespace App\Http\Controllers;

use App\ExperienceTypesCategory;
use App\Http\Requests\ExperienceTypesCategories\ExperienceTypesCategoriesStoreRequest;
use App\Http\Requests\ExperienceTypesCategories\ExperienceTypesCategoriesUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\ExperienceTypesCategory as ExperienceTypeCategoryResource;

class ExperienceTypesCategoriesController extends Controller
{
    /**
     * Display a listing of the all experience types categories.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        // get all experience type categories
        $experiences_type_categories = ExperienceTypesCategory::orderBy('id', 'DESC')->paginate(10);

        // return experience type categories as a collection
        return ExperienceTypeCategoryResource::collection($experiences_type_categories);
    }

    /**
     * Store a newly created experience type category in storage.
     *
     * @param ExperienceTypesCategoriesStoreRequest $request
     * @return ExperienceTypeCategoryResource
     */
    public function store(ExperienceTypesCategoriesStoreRequest $request)
    {
        // validate request and return validated data
        $validated = $request->validated();

        // create experience type category object and add other notification object properties
        $experiences_type_category =  new ExperienceTypesCategory($validated);

        // save experience type if transaction goes well
        if($experiences_type_category->save()){
            return new ExperienceTypeCategoryResource($experiences_type_category);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to create experience type categories'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Display the specified experience type category.
     *
     * @param  int  $id
     * @return ExperienceTypeCategoryResource
     */
    public function show($id)
    {
        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single experience type category
        try{
            $experiences_type_category = ExperienceTypesCategory::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["experience type category not found"];
            return response(['errors'=> $errors], 404);
        }

        // return single experience type category as a resource
        return new ExperienceTypeCategoryResource($experiences_type_category);
    }

    /**
     * get all categories belonging to a particular experience type using id
     * @param $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getCategoryByExperienceTypeId($id){
        // get categories using experience type id
        $experience_categories = ExperienceTypesCategory::where('experiences_type_id', $id)->paginate(10);

        return ExperienceTypeCategoryResource::collection($experience_categories);
    }

    /**
     * Update the specified experience type category in storage.
     *
     * @param ExperienceTypesCategoriesUpdateRequest $request
     * @param  int $id
     * @return ExperienceTypeCategoryResource
     */
    public function update(ExperienceTypesCategoriesUpdateRequest $request, $id)
    {
        // create experience types category object
        $experiences_type_category =  ExperienceTypesCategory::findOrFail($id);

        // validate request and return validated data
        $validated = $request->validated();

        //add other experience type category object properties
        $experiences_type_category->update($validated);

        //save experience type category if transaction goes well
        if($experiences_type_category->save()){
            return new ExperienceTypeCategoryResource($experiences_type_category);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to update experience type category'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Remove the specified experience type category from storage.
     *
     * @param  int  $id
     * @return ExperienceTypeCategoryResource
     */
    public function destroy($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single experience type category
        try{
            $experiences_type_category = ExperienceTypesCategory::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["experience type category entry not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete experience type category
        if($experiences_type_category->delete()){
            return new ExperienceTypeCategoryResource($experiences_type_category);
        }

        $errors = ['unknown error occurred while trying to delete experience type category'];
        return response(['errors'=> $errors], 500);
    }
}
