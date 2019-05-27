<?php

namespace App\Http\Controllers;

use App\FoodMenu;
use App\Http\Requests\FoodMenus\FoodMenuStoreRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Resources\FoodMenu as FoodMenuResource;
class FoodMenusController extends Controller
{
    /**
     * Display a listing of the all food menus.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $limit = $request->has('_limit')? $request->_limit : 20;

        // get all food menus
        $food_menu = FoodMenu::orderBy('id', 'DESC')->paginate($limit);

        // return food menu as a resource
        return FoodMenuResource::collection($food_menu);
    }

    /**
     * Store a newly created food menu in storage.
     *
     * @param FoodMenuStoreRequest $request
     * @return FoodMenuResource
     */
    public function store(FoodMenuStoreRequest $request)
    {
        // validate request and return validated data
        $validated = $request->validated();

        // create food menu object and add other object properties
        $food_menu =  new FoodMenu($validated);

        // save food menu if transaction goes well
        if($food_menu->save()){
            return new FoodMenuResource($food_menu);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to create food menu'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Display the specified food menu by id.
     *
     * @param  int  $id
     * @return FoodMenuResource
     */
    public function show($id)
    {
        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single food menu
        try{
            $food_menu = FoodMenu::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["food menu not found"];
            return response(['errors'=> $errors], 404);
        }

        // return single food menu as a resource
        return new FoodMenuResource($food_menu);
    }

    /**
     * Update the specified food menu in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return FoodMenuResource
     */
    public function update(Request $request, $id)
    {
        // create food menu object
        $food_menu =  FoodMenu::findOrFail($id);

        // validate request and return validated data
        $validated = $request->validated();

        // add other food menu object properties
        $food_menu->update($validated);

        // save food menu if transaction goes well
        if($food_menu->save()){
            return new FoodMenuResource($food_menu);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to update food menu'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Remove the specified food menu from storage.
     *
     * @param  int  $id
     * @return FoodMenuResource
     */
    public function destroy($id)
    {
        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single food menu
        try{
            $food_menu = FoodMenu::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["food menu not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete food menu
        if($food_menu->delete()){
            return new FoodMenuResource($food_menu);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to delete food menu'];
        return response(['errors'=> $errors], 500);
    }
}
