<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderItems\OrderItemsStoreRequest;
use App\OrderItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Resources\OrderItem as OrderItemsResource;

class OrderItemsController extends Controller
{
    /**
     * Display a listing of the all order items.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $limit = $request->has('_limit')? $request->_limit : 20;

        // get all order items
        $order_items = OrderItem::getBySearch($request)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        // return order items as a collection
        return OrderItemsResource::collection($order_items);
    }


    /**
     * Store a newly created order item in storage.
     *
     * @param OrderItemsStoreRequest $request
     * @return OrderItemsResource
     */
    public function store(OrderItemsStoreRequest $request)
    {
        // validate request and return validated data
        $validated = $request->validated();

        // create order item object and add properties
        $order_item =  new OrderItem($validated);

        // save order item if transaction goes well
        if($order_item->save()){
            return new OrderItemsResource($order_item);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to create order item'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Display the specified order item.
     *
     * @param  int  $id
     * @return OrderItemsResource
     */
    public function show($id)
    {
        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single order item
        try{
            $order_item = OrderItem::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["order item not found"];
            return response(['errors'=> $errors], 404);
        }

        // return single order item as a resource
        return new OrderItemsResource($order_item);
    }


    /**
     * Update the specified order item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return OrderItemsResource
     */
    public function update(Request $request, $id)
    {
        // create order item object
        $order_item =  OrderItem::findOrFail($id);

        // validate request and return validated data
        $validated = $request->validated();

        //add other order item object properties
        $order_item->update($validated);

        //save order item if transaction goes well
        if($order_item->save()){
            return new OrderItemsResource($order_item);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to update order item'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * get all order items from an order
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getOrderItemsByOrderId(Request $request, $id){

        $limit = $request->has('_limit')? $request->_limit : 20;

        $order_items = OrderItem::where('order_id', $id)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        return OrderItemsResource::collection($order_items);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return OrderItemsResource
     */
    public function destroy($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single order item
        try{
            $order_item = OrderItem::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["order item entry not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete order item
        if($order_item->delete()){
            return new OrderItemsResource($order_item);
        }

        $errors = ['unknown error occurred while trying to delete order item'];
        return response(['errors'=> $errors], 500);
    }
}
