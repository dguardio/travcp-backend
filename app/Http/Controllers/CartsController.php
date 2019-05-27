<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Http\Requests\Carts\CartsStoreRequest;
use App\Http\Requests\Carts\CartsUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Resources\Cart as CartsResource;
class CartsController extends Controller
{
    /**
     * Display a listing of all carts.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $limit = $request->has('_limit')? $request->_limit : 20;

        // get all carts
        $cart = Cart::orderBy('id', 'DESC')->paginate($limit);

        // return cart as a collection
        return CartsResource::collection($cart);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CartsStoreRequest $request
     * @return CartsResource
     */
    public function store(CartsStoreRequest $request)
    {
        // validate request and return validated data
        $validated = $request->validated();

        // create cart object and add properties
        $cart = new Cart($validated);

        // save cart item if transaction goes well
        if($cart->save()){
            return new CartsResource($cart);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to create cart'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return CartsResource
     */
    public function show($id)
    {
        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single cart
        try{
            $cart = Cart::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["cart not found"];
            return response(['errors'=> $errors], 404);
        }

        // return single cart as a resource
        return new CartsResource($cart);
    }

    /**
     * get cart of a specified user
     * @param $id
     * @return CartsResource
     */
    public function getUserCart($id){
        // get single cart
        try{
            $cart = Cart::where('user_id', $id)->firstOrFail();
        }catch (ModelNotFoundException $e){
            $errors = ["cart not found"];
            return response(['errors'=> $errors], 404);
        }

        // return as resource
        return new CartsResource($cart);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CartsUpdateRequest $request
     * @param  int $id
     * @return CartsResource
     */
    public function update(CartsUpdateRequest $request, $id)
    {
        // create cart object
        $cart =  Cart::findOrFail($id);

        // validate request and return validated data
        $validated = $request->validated();

        //add other cart object properties
        $cart->update($validated);

        //save cart if transaction goes well
        if($cart->save()){
            return new CartsResource($cart);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to update cart'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return CartsResource
     */
    public function destroy($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single cart
        try{
            $cart = Cart::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["cart entry not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete cart
        if($cart->delete()){
            return new CartsResource($cart);
        }

        $errors = ['unknown error occurred while trying to delete cart'];
        return response(['errors'=> $errors], 500);
    }
}
