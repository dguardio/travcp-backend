<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPayments\UserPaymentsStoreRequest;
use App\Http\Requests\UserPayments\UserPaymentsUpdateRequest;
use App\UserPayment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\UserPayment as UserPaymentsResource;
use Illuminate\Http\Request;

class UserPaymentsController extends Controller
{
    /**
     * Display a listing of the all user payments.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $limit = $request->has('_limit')? $request->_limit : 20;

        // get user payments
        $user_payments = UserPayment::getBySearch($request)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        // return collection of user payments as a resource
        return UserPaymentsResource::collection($user_payments);
    }

    /**
     * get user payments by transaction id
     * @param $id
     * @return UserPaymentsResource
     */
    public function getUserPaymentByTransactionId($id){
        try{
            // try to get a single user payments by transaction id
            $user_payments = UserPayment::where('transaction_id', '$id')->firstOrFail();
        }catch (ModelNotFoundException $e){
            $errors = ["user payment with transaction id ".$id." not found"];
            return response(['errors'=> $errors], 404);
        }

        // return collection of user payments as a resource
        return new UserPaymentsResource($user_payments);
    }
    /**
     * Store a newly created user payment entry in storage.
     *
     * @param UserPaymentsStoreRequest $request
     * @return UserPaymentsResource
     */
    public function store(UserPaymentsStoreRequest $request)
    {
        // validate request and return validated data
        $validated = $request->validated();

        // create user payment object and add other user payments object properties
        $user_payment =  new UserPayment($validated);

        // save user payment if transaction goes well
        if($user_payment->save()){
            return new UserPaymentsResource($user_payment);
        }

        return new UserPaymentsResource(null);
    }

    /**
     * Display the specified user payment entry.
     *
     * @param  int  $id
     * @return UserPaymentsResource
     */
    public function show($id)
    {
        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single user payments
        try{
            $user_payment = UserPayment::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["user payment entry not found"];
            return response(['errors'=> $errors], 404);
        }

        // return single user payment as a resource
        return new UserPaymentsResource($user_payment);
    }

    /**
     * /**
     * Update the specified user payment entry in storage.
     *
     * @param UserPaymentsUpdateRequest $request
     * @param  int $id
     * @return UserPaymentsResource
     */
    public function update(UserPaymentsUpdateRequest $request, $id)
    {
        // create user payment object
        $user_payment =  UserPayment::findOrFail($id);

        // validate request and return validated data
        $validated = $request->validated();

        // add other user payment object properties
        $user_payment->update($validated);

        // save payment if transaction goes well
        if($user_payment->save()){
            return new UserPaymentsResource($user_payment);
        }

        return new UserPaymentsResource(null);
    }

    /**
     * Remove the specified user payment entry from storage.
     *
     * @param  int  $id
     * @return UserPaymentsResource
     */
    public function destroy($id)
    {
        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single user payment
        try{
            $user_payment = UserPayment::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["user payment entry not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete user payment entry
        if($user_payment->delete()){
            return new UserPaymentsResource($user_payment);
        }

        return new UserPaymentsResource(null);
    }
}
