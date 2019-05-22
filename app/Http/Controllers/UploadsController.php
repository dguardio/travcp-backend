<?php

namespace App\Http\Controllers;

use App\Http\Requests\Uploads\UploadsStoreRequest;
use App\Http\Requests\Uploads\UploadsUpdateRequest;
use App\Http\Resources\FullUpload;
use App\Traits\Uploads;
use App\Upload;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\Upload as UploadsResource;

class UploadsController extends Controller
{
    use Uploads;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        // get all uploads
        $upload = Upload::orderBy('id', 'DESC')->paginate(20);

        // return uploads as a resource
        return FullUpload::collection($upload);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return FullUpload
     */
    public function show($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single upload
        try{
            $upload = Upload::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["upload entry not found"];
            return response(['errors'=> $errors], 404);
        }

        //return single booking as a resource
        return new FullUpload($upload);
    }

    /**
     * store a new upload
     * @param UploadsStoreRequest $request
     * @return FullUpload
     */
    public function store(UploadsStoreRequest $request){

        // get validated
        $validated = $request->validated();

        // create upload data;
        unset($validated["image"]);

        // store file and get upload id;
        $upload_id = $this->storeFile($request, 'image', $validated);

        // return data || error
        if($upload_id === -1){
            $errors = ["error while storing upload"];
            return response(['errors'=> $errors], 500);
        }else{
            return new FullUpload(Upload::find($upload_id));
        }

    }

    /**
     * update upload with a specified id
     * @param UploadsUpdateRequest $request
     * @param $id
     * @return FullUpload
     */
    public function update(UploadsUpdateRequest $request, $id){

        // get validated
        $validated = $request->validated();

        // create upload data;
        unset($validated["image"]);

        // store file and get upload id;
        $upload_id = $this->updateFile($request, $id, 'image', $validated);

        // return data || error
        if($upload_id === -1){
            $errors = ["error while storing upload"];
            return response(['errors'=> $errors], 500);
        }else{
            return new FullUpload(Upload::find($upload_id));
        }
    }

    /**
     * delete specified image from database
     * @param $id
     * @return FullUpload
     */
    public function destroy($id){

        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single upload
        try{
            $upload = Upload::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["upload entry not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete upload
        if($upload->delete()){
            return new FullUpload($upload);
        }

        $errors = ['unknown error occurred while trying to delete upload'];
        return response(['errors'=> $errors], 500);
    }


}
