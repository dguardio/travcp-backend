<?php

namespace App\Http\Controllers;

use App\Http\Requests\Orders\OrdersStoreRequest;
use App\Http\Requests\Orders\OrdersUpdateRequest;
use App\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\Order as OrdersResource;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Display a listing of all orders.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        // get all orders
        $orders = Order::getBySearch($request)
            ->orderBy('id', 'DESC')
            ->paginate(20);

        // return orders as a collection
        return OrdersResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OrdersStoreRequest $request
     * @return OrdersResource
     */
    public function store(OrdersStoreRequest $request)
    {
        // validate request and return validated data
        $validated = $request->validated();

        // create order object and add properties
        $order =  new Order($validated);

        // save order if transaction goes well
        if($order->save()){
            return new OrdersResource($order);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to create order'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return OrdersResource
     */
    public function show($id)
    {
        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single order
        try{
            $order = Order::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["order not found"];
            return response(['errors'=> $errors], 404);
        }

        // return single order as a resource
        return new OrdersResource($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OrdersUpdateRequest $request
     * @param  int $id
     * @return OrdersResource
     */
    public function update(OrdersUpdateRequest $request, $id)
    {
        // create order object
        $order =  Order::findOrFail($id);

        // validate request and return validated data
        $validated = $request->validated();

        //add other order object properties
        $order->update($validated);

        //save order if transaction goes well
        if($order->save()){
            return new OrdersResource($order);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to update order'];
        return response(['errors'=> $errors], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return OrdersResource
     */
    public function destroy($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single order
        try{
            $order = Order::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["order entry not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete order
        if($order->delete()){
            return new OrdersResource($order);
        }

        $errors = ['unknown error occurred while trying to delete order'];
        return response(['errors'=> $errors], 500);
    }
}
