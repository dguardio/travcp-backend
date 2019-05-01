<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\UsersUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\User as UserResource;
use App\User;
class UsersController extends Controller
{
    /**
     * Display a listing of all users, pagination data included.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        // get all users
        $users = User::orderBy('id', 'DESC')->paginate(10);

        // return users as a collection
        return UserResource::collection($users);
    }

    /**
     * Display the specified user's data.
     *
     * @param  int  $id
     * @return UserResource
     */
    public function show($id)
    {
        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single user
        try{
            $user = User::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["user not found"];
            return response(['errors'=> $errors], 404);
        }

        // return single user as a resource
        return new UserResource($user);
    }

    /**
     * Update the specified user data in storage.
     *
     * @param UsersUpdateRequest $request
     * @param  int $id
     * @return UserResource
     */
    public function update(UsersUpdateRequest $request, $id)
    {
        // create user object
        $users =  User::findOrFail($id);

        // validate request and return validated data
        $validated = $request->validated();

        // add other user object properties
        $users->update($validated);

        // save user if transaction goes well
        if($users->save()){
            return new UserResource($users);
        }

        return new UserResource(null);
    }

    /**
     * Remove the specified user's data from storage.
     *
     * @param  int  $id
     * @return UserResource
     */
    public function destroy($id)
    {
        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single user
        try{
            $user = User::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["user not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete user
        if($user->delete()){
            return new UserResource($user);
        }

        return new UserResource(null);
    }
}
