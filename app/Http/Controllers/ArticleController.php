<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Articles;
class ArticleController extends Controller
{
    public function index()
    {
        
        $articles = Articles::orderBy('id', 'DESC')->paginate(10);
        // return MerchantResource::collection($merchant);
        return $articles;
    }
   
    public function newarticle()
    {
        $this->validate(\request(),[
            'title'=>'required|min:3',
            "content" => "required|min:3",
            'user_id'=>'required',
        ]);
        // $data = request()->all();
        $data= [
            'title' => request()->title,
            'content' => request()->content,
            'user_id' => request()->userid
        ];
       
        $u = Articles::insert($data);
        return $u;
    }

   
    public function view($id)
    {
        // get articles data from db
       $articles = Articles::select('id', 'title','content', 'user_id')->findOrFail($id);

    //    return new UserResource($merchant);
        return $articles;
    }

    /**
     * update articles 
     * @param $id
     * 
     */
    public function update($id)
    {
        // validate request data
        $this->validate(\request(),[
            'title'=>'required|min:3',
            "content" => "required|min:3",
            
        ]);

        // create data array
        $data= [
            'title' => request()->title,
            'content' => request()->content,
        ];

        // return user as a resource
        $u = Articles::where('id',$id)->update($data);
        return $u;
        // return $data;
    }
    public function deletearticle()
    {
        $id= request()->id;
       $q= Articles::where('id',$id)->delete();
         return $q;
        // }
    }
}
