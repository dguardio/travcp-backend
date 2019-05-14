<?php

namespace App\Http\Controllers;

use App\Upload;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Resources\Upload as UploadsResource;
class UploadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        // get all uploads
        $upload = Upload::orderBy('id', 'DESC')->paginate(10);

        // return uploads as a resource
        return UploadsResource::collection($upload);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return UploadsResource
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
        return new UploadsResource($upload);
    }


}
