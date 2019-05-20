<?php

namespace App\Http\Controllers;

use App\Http\Requests\MerchantPayments\MerchantPaymentsStoreRequest;
use App\Http\Requests\MerchantPayments\MerchantPaymentsUpdateRequest;
use App\MerchantPayment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\MerchantPayment as MerchantPaymentResource;
class MerchantPaymentsController extends Controller
{

    /**
     * Display a listing of the all merchant payments with paginate.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        //get merchant payments
        $merchant_payment = MerchantPayment::orderBy('id', 'DESC')->paginate(20);

        //return collection of merchant payments as a resource
        return MerchantPaymentResource::collection($merchant_payment);
    }


    /**
     * Store a newly created merchant payment entry in storage.
     *
     * @param MerchantPaymentsStoreRequest $request
     * @return MerchantPaymentResource
     */
    public function store(MerchantPaymentsStoreRequest $request)
    {
        // validate request and return validated data
        $validated = $request->validated();

        // create merchant payment object and add other merchant payment object properties
        $merchant_payment =  new MerchantPayment($validated);

        //save merchant payment if transaction goes well
        if($merchant_payment->save()){
            return new MerchantPaymentResource($merchant_payment);
        }

        return new MerchantPaymentResource(null);
    }


    /**
     * Display the specified merchant payment data.
     *
     * @param  int  $id
     * @return MerchantPaymentResource
     */
    public function show($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single merchant payment
        try{
            $merchant_payment = MerchantPayment::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["merchant payment not found"];
            return response(['errors'=> $errors], 404);
        }

        //return single merchant payment as a resource
        return new MerchantPaymentResource($merchant_payment);
    }


    /**
     * Update the specified merchant payment entry in storage.
     *
     * @param MerchantPaymentsUpdateRequest $request
     * @param  int $id
     * @return MerchantPaymentResource
     */
    public function update(MerchantPaymentsUpdateRequest $request, $id)
    {
        // create merchant payment object
        $merchant_payment =  MerchantPayment::findOrFail($id);

        // validate request and return validated data
        $validated = $request->validated();

        //add other merchant payment object properties
        $merchant_payment->update($validated);

        //save merchant payment if transaction goes well
        if($merchant_payment->save()){
            return new MerchantPaymentResource($merchant_payment);
        }

        return new MerchantPaymentResource(null);
    }


    /**
     * get all payments paid to a particular merchant using the merchant id
     * @param $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getMerchantPaymentsByMerchantId($id){
        // get merchant payment by merchant id
        $merchant_payment = MerchantPayment::where('merchant_id', $id)->orderBy('id', 'DESC')->paginate(10);

        // return collection of merchant payment as a resource
        return MerchantPaymentResource::collection($merchant_payment);
    }


    /**
     * Remove the specified merchant payment entry from storage.
     *
     * @param  int  $id
     * @return MerchantPaymentResource
     */
    public function destroy($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single merchant payment
        try{
            $merchant_payment = MerchantPayment::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["merchant payment entry not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete merchant payment
        if($merchant_payment->delete()){
            return new MerchantPaymentResource($merchant_payment);
        }

        return new MerchantPaymentResource(null);
    }
}
