<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AffiliateApplication;

class AffiliateApplicationsController extends Controller
{
    public function store(Request $request)
    {
        $params = $request->all();
        $previous = $this->retrieveByUserId($params['user_id']);
        if (! $previous) return response()->json([], 400);
        $affiliate_application = AffiliateApplication::create($params);
        $returned_data = $this->retrieve($affiliate_application->id);
        return response()->json($returned_data, 201);
    }

    public function retrieve($id)
    {
        return AffiliateApplication::with('user:id,first_name,surname,name')
            ->where('id', $id)
            ->first();
    }

    public function retrieveByUserId($user_id)
    {
        return AffiliateApplication::with('user:id,first_name,surname,name')
            ->where('user_id', $user_id)
            ->first();
    }

    public function getByUserId($user_id)
    {
        $data = $this->retrieveByUserId($user_id);
        return response()->json($data, 200);
    }
}
