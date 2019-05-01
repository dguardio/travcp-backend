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
            // 'price'=>'required|min:3',
            // "destination" => "required|min:3",
            'role'=>'required',
        ]);
        // $data = request()->all();
        $data= [
        'role' => request()->role];
        $userid = request()->userId;
        $u = User::where('id',$userid)->update($data);
        return $u;
    }

    public function profile()
    {
        $id = request()->id;
       $data = User::select('id', 'first_name','surname', 'role')->findOrFail($id);
       return $data;
    }
    
    public function updateprofile()
    {
        $this->validate(\request(),[
            'first_name'=>'required|min:3',
            "surname" => "required|min:3",
            'role'=>'required',
        ]);
        $userid = request()->id;
        $data= [
            'first_name' => request()->first_name,
            'surname' => request()->surname,
            'role' => request()->role,
        ];
        $u = User::where('id',$userid)->update($data);
        return $u;
    }
}
