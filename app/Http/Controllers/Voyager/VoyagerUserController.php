<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Http\Request;

class VoyagerUserController extends \TCG\Voyager\Http\Controllers\VoyagerUserController
{
    public function store(Request $request){
        return parent::store($request);
    }
}
