<?php

/**
 * Created by PhpStorm.
 * Developer: TheDarkKid
 * Date: 5/13/2019
 * Time: 3:20 PM
 */

namespace App\Traits;
use App\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait Uploads
{

    public $path = "/uploads";

    /*
    |--------------------------------------------------------------------------
    | Wrapper Methods
    |--------------------------------------------------------------------------
    |
    | These methods are used to call the different upload method based on environmental variables
    |
    */

    /**
     * wrapper method for storing a file
     * @param Request $request
     * @param string $file_field_name
     * @param null $extras
     * @return \Illuminate\Http\Response|int|mixed
     */
    public function storeFile(Request $request, $file_field_name='image', $extras=null){
        if(config('app.env') != "production"){
            return $this->devStoreFile($request, $file_field_name, $extras);
        }else{
            return $this->prodStoreFile($request, $file_field_name, $extras);
        }
    }

    /**
     * wrapper method for updating a file
     * @param Request $request
     * @param $id
     * @param string $file_field_name
     * @param null $extras
     * @return \Illuminate\Http\Response|mixed
     */
    public function updateFile(Request $request, $id, $file_field_name='image', $extras=null){
        if(config('app.env') != "production"){
            return $this->devUpdateFile($request, $id, $file_field_name, $extras);
        }else{
            return $this->prodUpdateFile($request, $id, $file_field_name, $extras);
        }
    }

    /**
     * wrapper method for multiple image uploads
     * @param Request $request
     * @param string $multi_field_name
     * @param null $extras
     * @return array
     */
    public function multipleImagesUpload(Request $request, $multi_field_name='images', $extras=null){
        if(config('app.env') != "production"){
            return $this->devMultipleImagesUpload($request, $multi_field_name, $extras);
        }else{
            return $this->prodMultipleImagesUpload($request, $multi_field_name, $extras);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | File Storage
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * Store a newly created file in database.
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $file_field_name
     * @param null $extras [optional]
     * @return \Illuminate\Http\Response
     */
    private function devStoreFile(Request $request, $file_field_name='image', $extras=null)
    {
        $uploadsId = -1;

        // handle file upload
        if($request->hasFile($file_field_name)){
            // get file extension
            $extension = $request->file($file_field_name)->getClientOriginalExtension();

            // convert file to longtext
            $base64 = 'data:image/' . $extension . ';base64,' . base64_encode(file_get_contents($request->file($file_field_name)));

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
     * Store a newly created file in production database
     * @param Request $request
     * @param string $file_field_name
     * @param null $extras
     * @return int|mixed
     */
    private function prodStoreFile(Request $request, $file_field_name='image', $extras=null)
    {
        $uploadsId = -1;

        // handle file upload
        if($request->hasFile($file_field_name)){

            // store file
            $path = $request->file($file_field_name)->store($this->path);

            // create new upload object
            $upload = new Upload();

            // fill with extra data
            if($extras !== null) $upload->fill($extras);

            // add upload data
            $upload->upload_data = $path;

            // save to db
            if($upload->save()){
                $uploadsId = $upload->id;
            }
        }

        return $uploadsId;
    }


    /*
    |--------------------------------------------------------------------------
    |  File Updates
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * Update the specified file in database in development environment.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @param string $file_field_name
     * @param null $extras [optional]
     * @return \Illuminate\Http\Response
     */
    private function devUpdateFile(Request $request, $id, $file_field_name='image', $extras=null)
    {
        // create upload object
        $upload =  Upload::findOrFail($id);

        // fill with extra data
        if($extras !== null) $upload->fill($extras);

        // set upload id
        $uploadsId = $upload->id;

        // handle file upload
        if($request->hasFile($file_field_name)){
            // get file extension
            $extension = $request->file($file_field_name)->getClientOriginalExtension();

            // convert file to longtext
            $base64 = 'data:image/' . $extension . ';base64,' . base64_encode(file_get_contents($request->file($file_field_name)));

            // update upload
            $upload->upload_data = $base64;

            // save to db
            if($upload->save()){
                $uploadsId = $upload->id;
            }

        }

        return $uploadsId;

    }

    /**
     * Update the specified file in database in production environment.
     *
     * @param Request $request
     * @param $id
     * @param string $file_field_name
     * @param null $extras
     * @return mixed
     */
    private function prodUpdateFile(Request $request, $id, $file_field_name='image', $extras=null)
    {
        // create upload object
        $upload =  Upload::findOrFail($id);

        // fill with extra data
        if($extras !== null) $upload->fill($extras);

        // set upload id
        $uploadsId = $upload->id;

        $old_file_path = $upload->upload_data;

        // handle file upload
        if($request->hasFile($file_field_name)){
            // store file
            $path = $request->file($file_field_name)->store($this->path);

            // update upload
            $upload->upload_data = $path;

            // delete old file
            Storage::delete($old_file_path);

            // save to db
            if($upload->save()){
                $uploadsId = $upload->id;
            }

        }

        return $uploadsId;

    }


    /*
    |--------------------------------------------------------------------------
    | Multiple File Uploads
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * multiple file upload for production
     * @param Request $request
     * @param string $multi_field_name
     * @param null $extras
     * @return array
     */

    private function devMultipleImagesUpload(Request $request, $multi_field_name='images', $extras=null){
        $saved_files = array();
        if($files=$request->file($multi_field_name)){
            foreach($files as $file){

                // get file extension
                $extension = $file->getClientOriginalExtension();

                // convert file to longtext
                $base64 = 'data:image/' . $extension . ';base64,' . base64_encode(file_get_contents($file));

                // save file in db
                // create new upload object
                $upload = new Upload();

                // fill with extra data
                if($extras !== null) $upload->fill($extras);

                // add upload data
                $upload->upload_data = $base64;

                // save to db
                if($upload->save()){
                    array_push($saved_files, $upload->id);
                }
            }
        }

        return $saved_files ;
    }

    /**
     * multiple image upload for production
     * @param Request $request
     * @param string $multi_field_name
     * @param null $extras
     * @return array
     */
    private function prodMultipleImagesUpload(Request $request, $multi_field_name='images', $extras=null){
        $saved_files = array();
        if($files=$request->file($multi_field_name)){
            foreach($files as $file){
                // store file
                $path = $file->store($this->path);

                // create new upload object
                $upload = new Upload();

                // fill with extra data
                if($extras !== null) $upload->fill($extras);

                // add upload data
                $upload->upload_data = $path;

                // save to db
                if($upload->save()){
                    array_push($saved_files, $upload->id);
                }
            }
        }

        return $saved_files ;
    }

    /*
    |--------------------------------------------------------------------------
    |  File Deletion
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * delete file
     * @param $file_path
     */
    public function deleteFile($file_path){
        if(config('app.env') == "production"){
            Storage::delete($file_path);
        }
    }
}