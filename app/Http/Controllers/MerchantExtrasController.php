<?php

namespace App\Http\Controllers;

use App\Http\Requests\MerchantExtras\MerchantExtrasStoreRequest;
use App\Http\Requests\MerchantExtras\MerchantExtrasUpdateRequest;
use App\MerchantExtra;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\MerchantExtra as MerchantExtraResource;
use Illuminate\Support\Facades\Storage;

class MerchantExtrasController extends Controller
{
    private $identity_documents_dir = 'public/identity_documents';
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
        $fileNameToStore = $this->storeFile($request);

        // create new merchant extras based of the validated data
        unset($validated['identity_document']);

        $merchant_extra = new MerchantExtra($validated);
        $merchant_extra->identity_document_file = $fileNameToStore;

        // save merchant extra
        if($merchant_extra->save()){
            return new MerchantExtraResource($merchant_extra);
        }

        // return error if couldn't be saved
        $errors = ['unknown error trying to create a booking'];
        return response(['errors'=>$errors], 500);
    }

    /**
     * store file from request
     * @param $request
     * @return string
     */
    private function storeFile($request){
        // handle file upload
        if($request->hasFile('identity_document')){
            // get filename with extension
            $filenameWithExt = $request->file('identity_document')->getClientOriginalName();

            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // Get just ext
            $extension = $request->file('identity_document')->getClientOriginalExtension();

            //Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;

            // Upload Image
            $path = $request->file('identity_document')->storeAs($this->identity_documents_dir, $fileNameToStore);
        }else{
            $fileNameToStore = 'noimage.jpg';
        }

        return $fileNameToStore;
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

        // delete previous image
        if($merchant_extra->identity_document_file !== "noimage.jpg"
            && $merchant_extra->identity_document_file !== null
            && $merchant_extra->identity_document_file !== ""
            && $request->hasFile('identity_document')){

            Storage::delete($this->identity_documents_dir."/".$merchant_extra->identity_document_file);
        }
        // store file and get filename
        $fileNameToStore = $this->storeFile($request);

        // update the merchant extra
        unset($validated['identity_document']);
        $validated['identity_document_file'] = $fileNameToStore;
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

        // delete previous image
        if($merchant_extra->identity_document_file !== "noimage.jpg"
            && $merchant_extra->identity_document_file !== null
            && $merchant_extra->identity_document_file !== ""
            && $request->hasFile('identity_document')){

            Storage::delete($this->identity_documents_dir."/".$merchant_extra->identity_document_file);
        }
        // store file and get filename
        $fileNameToStore = $this->storeFile($request);

        // update the merchant extra
        unset($validated['identity_document']);
        $validated['identity_document_file'] = $fileNameToStore;

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
