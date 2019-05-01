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
        dd('$data');

        // $userid = $request->$userId;
        // $user = User::findOrFail($userId);
        $data = request()->all();
        // $u = Auth::user();
        // $u = User::where('id',$userid)->update($data);
        return $data;
        
    }
}
