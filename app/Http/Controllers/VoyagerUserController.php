<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VoyagerUserController extends \TCG\Voyager\Http\Controllers\VoyagerUserController
{
    public function store(Request $request){
        $name = $request->input("first_name")." ". $request->input("surname");
        $request->merge(["name"=>$name]);

        return parent::store($request);
    }
}
