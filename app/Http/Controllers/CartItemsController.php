<?php

namespace App\Http\Controllers;

use App\CartItem;
use App\Http\Requests\CartItems\CartItemsStoreRequest;
use App\Http\Requests\CartItems\CartItemsUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\CartItem as CartItemsResources;

class CartItemsController extends Controller
{
    /**
     * Display a listing of cart items.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        // get all cart items
        $cart_items = CartItem::orderBy('id', 'DESC')->paginate(10);

        // return cart items as a collection
        return CartItemsResources::collection($cart_items);
    }

    /**
     * Store a newly created cart item in storage.
     *
     * @param CartItemsStoreRequest $request
     * @return CartItemsResources
     */
    public function store(CartItemsStoreRequest $request)
    {
        // validate request and return validated data
        $validated = $request->validated();

        // create cart item object and add properties
        $cart_item =  new CartItem($validated);

        // save cart item if transaction goes well
        if($cart_item->save()){
            return new CartItemsResources($cart_item);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to create cart item'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Display the specified cart item.
     *
     * @param  int  $id
     * @return CartItemsResources
     */
    public function show($id)
    {
        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single cart item
        try{
            $cart_item = CartItem::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["cart item not found"];
            return response(['errors'=> $errors], 404);
        }

        // return single cart item as a resource
        return new CartItemsResources($cart_item);
    }

    /**
     * Update the specified cart item in storage.
     *
     * @param CartItemsUpdateRequest $request
     * @param  int $id
     * @return CartItemsResources
     */
    public function update(CartItemsUpdateRequest $request, $id)
    {
        // create cart item object
        $cart_item =  CartItem::findOrFail($id);

        // validate request and return validated data
        $validated = $request->validated();

        //add other cart item object properties
        $cart_item->update($validated);

        //save cart item if transaction goes well
        if($cart_item->save()){
            return new CartItemsResources($cart_item);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to update cart item'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Remove the specified cart item from storage.
     *
     * @param  int  $id
     * @return CartItemsResources
     */
    public function destroy($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single cart item
        try{
            $cart_item = CartItem::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["cart item entry not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete cart item
        if($cart_item->delete()){
            return new CartItemsResources($cart_item);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to delete cart item'];
        return response(['errors'=> $errors], 500);
    }
}
