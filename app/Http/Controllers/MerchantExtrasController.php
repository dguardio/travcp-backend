<?php

namespace App\Http\Controllers;

use App\Http\Requests\MerchantExtras\MerchantExtrasStoreRequest;
use App\Http\Requests\MerchantExtras\MerchantExtrasUpdateRequest;
use App\MerchantExtra;
use App\Traits\Uploads;
use App\Upload;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\MerchantExtra as MerchantExtraResource;

class MerchantExtrasController extends Controller
{
    use Uploads;
    /**
     * Display a listing of the all merchant extras.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        // get all merchant extras from the db
        $merchant_extras = MerchantExtra::orderBy('id', 'DESC')->paginate(10);

        // return the merchant extras as a resource
        return MerchantExtraResource::collection($merchant_extras);

    }

    /**
     * Store a newly created merchant extras in storage.
     *
     * @param MerchantExtrasStoreRequest $request
     * @return MerchantExtraResource
     */
    public function store(MerchantExtrasStoreRequest $request)
    {
        // get validated data
        $validated = $request->validated();

        // store file and get filename
        $uploads_id = $this->storeFile($request, 'identity_document');

        // create new merchant extras based of the validated data
        unset($validated['identity_document']);

        $merchant_extra = new MerchantExtra($validated);
        $merchant_extra->upload_id = $uploads_id;

        // save merchant extra
        if($merchant_extra->save()){
            if($merchant_extra->upload_id !== -1){
                $upload = Upload::findOrFail($merchant_extra->upload_id);
                $upload->merchant_extra_id = $merchant_extra->id;
                $upload->save();
            }
            return new MerchantExtraResource($merchant_extra);
        }

        // return error if couldn't be saved
        $errors = ['unknown error trying to create a booking'];
        return response(['errors'=>$errors], 500);
    }


    /**
     * Display the specified merchant extra by id.
     *
     * @param  int  $id
     * @return MerchantExtraResource
     */
    public function show($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single merchant extra
        try{
            $merchant_extra = MerchantExtra::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["merchant extra not found"];
            return response(['errors'=> $errors], 404);
        }

        // return single merchant extra as a resource
        return new MerchantExtraResource($merchant_extra);
    }

    /**
     * get merchant extra by merchant id
     * @param $id
     * @return MerchantExtraResource
     */
    public function getMerchantExtrasByMerchantId($id){
        // get merchant extras by merchant id
        $merchant_extra = MerchantExtra::where('merchant_id', $id)->first();

        // return merchant extras as a resource
        return new MerchantExtraResource($merchant_extra);
    }

    /**
     * Update the specified merchant extra in storage.
     *
     * @param MerchantExtrasUpdateRequest $request
     * @param  int $id
     * @return MerchantExtraResource
     */
    public function update(MerchantExtrasUpdateRequest $request, $id)
    {
        // get validated data;
        $validated = $request->validated();

        // find merchant extra object for updating
        $merchant_extra = MerchantExtra::findOrFail($id);

        // get upload extra data
        $extras = ["merchant_extra_id" => $merchant_extra->id];

        // store file and get file upload id
        if($merchant_extra->upload_id !== -1){
            $upload_id = $this->updateFile($request, $merchant_extra->upload_id, 'identity_document');
        }else{
            $upload_id = $this->storeFile($request, 'identity_document', $extras);
        }

        // update the merchant extra
        unset($validated['identity_document']);
        $validated['upload_id'] = $upload_id;
        $merchant_extra->update($validated);

        // return updated collection as a resource
        return new MerchantExtraResource($merchant_extra);
    }

    /**
     * update merchant extra using merchant id
     * @param MerchantExtrasUpdateRequest $request
     * @param $id
     * @return MerchantExtraResource
     */
    public function updateByMerchantId(MerchantExtrasUpdateRequest $request, $id)
    {
        // get validated data;
        $validated = $request->validated();

        // get merchant extra
        $merchant_extra = MerchantExtra::where('merchant_id', $id)->get();

        // get upload extra data
        $extras = ["merchant_extra_id" => $merchant_extra->id];

        // store file and get upload id
        if($merchant_extra->upload_id !== -1){
            $upload_id = $this->updateFile($request, $merchant_extra->upload_id, 'identity_document');
        }else{
            $upload_id = $this->storeFile($request, 'identity_document', $extras);
        }

        // update the merchant extra
        unset($validated['identity_document']);
        $validated['upload_id'] = $upload_id;

        $merchant_extra->update($validated);

        // get merchant extra
        $merchant_extra = MerchantExtra::where('merchant_id', $id)->get();

        // return updated collection as a resource
        return new MerchantExtraResource($merchant_extra);
    }

    /**
     * Remove the specified merchant from storage using its id.
     *
     * @param  int  $id
     * @return MerchantExtraResource
     */
    public function destroy($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single merchant extra
        try{
            $merchant_extra = MerchantExtra::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["merchant payment entry not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete merchant extra
        if($merchant_extra->delete()){
            return new MerchantExtraResource($merchant_extra);
        }

        $errors = ['unknown error occurred while trying to delete merchant extra'];
        return response(['errors'=> $errors], 500);
    }
}
