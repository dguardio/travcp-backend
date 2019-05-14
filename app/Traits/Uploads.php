<?php
/**
 * Created by PhpStorm.
 * User: TheDarkKid
 * Date: 5/13/2019
 * Time: 3:20 PM
 */

namespace App\Traits;
use App\Upload;
use Illuminate\Http\Request;
use App\Http\Resources\Upload as UploadsResource;

trait Uploads
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $file_field_name
     * @return \Illuminate\Http\Response
     */
    public function storeFile(Request $request, $file_field_name='image', $extras=null)
    {
        $uploadsId = -1;

        // handle file upload
        if($request->hasFile($file_field_name)){
            // Get just extension
            $extension = $request->file($file_field_name)->getClientOriginalExtension();

            // convert file to longtext
            $base64 = 'data:image/' . $extension . ';base64,' . base64_encode($request->file($file_field_name));

            // create new upload object
            $upload = new Upload();

            // fill with extra data
            if($extras !== null) $upload->fill($extras);

            // add upload data
            $upload->upload_data = $base64;

            // save to db
            if($upload->save()){
                $uploadsId = $upload->id;
            }
        }

        return $uploadsId;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @param string $file_field_name
     * @return \Illuminate\Http\Response
     */
    public function updateFile(Request $request, $id, $file_field_name='image', $extras=null)
    {
        // create upload object
        $upload =  Upload::findOrFail($id);

        // fill with extra data
        if($extras !== null) $upload->fill($extras);

        // set upload id
        $uploadsId = $upload->id;

        // handle file upload
        if($request->hasFile($file_field_name)){
            // Get just extension
            $extension = $request->file($file_field_name)->getClientOriginalExtension();

            // convert file to longtext
            $base64 = 'data:image/' . $extension . ';base64,' . base64_encode($request->file($file_field_name));

            // update upload
            $upload->upload_data = $base64;

            // save to db
            if($upload->save()){
                $uploadsId = $upload->id;
            }

        }

        return $uploadsId;

    }

}