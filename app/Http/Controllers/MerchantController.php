<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

class  MerchantController extends Controller
{
    public function touroperator()
    {

    }
   
    public function register()
    {
        $this->validate(\request(),[
            'price'=>'required|min:3',
            "destination" => "required|min:3",
            'role'=>'required',
        ]);
        $data = request()->all();
        
        $u = Auth::user();
        return $u;
        
    }
}
