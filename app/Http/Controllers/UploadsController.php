<?php

namespace App\Http\Controllers;

use App\Http\Requests\Uploads\UploadsStoreRequest;
use App\Http\Requests\Uploads\UploadsUpdateRequest;
use App\Http\Resources\Plain;
use App\Traits\Uploads;
use App\Upload;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\Upload as UploadsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadsController extends Controller
{
    use Uploads;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $limit = $request->has('_limit')? $request->_limit : 20;

        // get all uploads
        $upload = Upload::orderBy('id', 'DESC')->paginate($limit);

        // return uploads as a resource
        return Plain::collection($upload);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Plain
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
        return new Plain($upload);
    }

    /**
     * store a new upload
     * @param UploadsStoreRequest $request
     * @return Plain
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
            return new Plain(Upload::find($upload_id));
        }

    }

    /**
     * update upload with a specified id
     * @param UploadsUpdateRequest $request
     * @param $id
     * @return Plain
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
            return new Plain(Upload::find($upload_id));
        }
    }

    /**
     * delete specified image from database
     * @param $id
     * @return Plain
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
            $this->removeFile($upload->upload_data);
            return new Plain($upload);
        }

        $errors = ['unknown error occurred while trying to delete upload'];
        return response(['errors'=> $errors], 500);
    }


}
