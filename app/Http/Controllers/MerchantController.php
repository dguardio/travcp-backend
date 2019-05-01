<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\User as UserResource;
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
            'role' => request()->role
        ];
        $userid = request()->userId;
        $u = User::where('id',$userid)->update($data);
        return $u;
    }

    /**
     * display merchant profile
     * @param $id
     * @return UserResource
     */
    public function profile($id)
    {
        // get merchant data from db
       $merchant = User::select('id', 'first_name','surname', 'role')->findOrFail($id);

       // return merchant data as a resource
       return new UserResource($merchant);
    }

    /**
     * update merchant profile
     * @param $id
     * @return UserResource
     */
    public function update($id)
    {
        // validate request data
        $this->validate(\request(),[
            'first_name'=>'required|min:3',
            "surname" => "required|min:3",
            'role'=>'required',
        ]);

        // create data array
        $data= [
            'first_name' => request()->first_name,
            'surname' => request()->surname,
            'role' => request()->role,
        ];

        // return user as a resource
        $user = User::where('id',$id)->update($data);
        return new UserResource($user);
    }
}
