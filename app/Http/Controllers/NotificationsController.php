<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notifications\NotificationsStoreRequest;
use App\Http\Requests\Notifications\NotificationsUpdateRequest;
use App\Notification;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\Notification as NotificationResource;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        // get notifications
        $notifications = Notification::orderBy('id', 'DESC')->paginate(10);

        // return collection of notifications as a resource
        return NotificationResource::collection($notifications);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param NotificationsStoreRequest $request
     * @return NotificationResource
     */
    public function store(NotificationsStoreRequest $request)
    {
        // create notification object
        $notification =  new Notification;

        // validate request and return validated data
        $validated = $request->validated();

        // add other notification object properties
       $notification->user_id = $validated['user_id'];
       $notification->notification_body = $validated["notification_body"];

        // save notification if transaction goes well
        if($notification->save()){
            return new NotificationResource($notification);
        }

        return new NotificationResource(null);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return NotificationResource
     */
    public function show($id)
    {
        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single notification
        try{
            $notification = Notification::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["notification not found"];
            return response(['errors'=> $errors], 404);
        }

        // return single notification as a resource
        return new NotificationResource($notification);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param NotificationsUpdateRequest $request
     * @param  int $id
     * @return NotificationResource
     */
    public function update(NotificationsUpdateRequest $request, $id)
    {
        // create notification object
        $notification =  Notification::findOrFail($id);

        // validate request and return validated data
        $validated = $request->validated();

        // add other notification object properties
        $notification->user_id = empty($validated['user_id'])? $notification->user_id : $validated['user_id'];
        $notification->notification_body = empty($validated["notification_body"])? $notification->notification_body : $validated["notification_body"];

        // save notification if transaction goes well
        if($notification->save()){
            return new NotificationResource($notification);
        }

        return new NotificationResource(null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return NotificationResource
     */
    public function destroy($id)
    {
        // validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        // try to get a single notification
        try{
            $notification = Notification::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["notification not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete notifications
        if($notification->delete()){
            return new NotificationResource($notification);
        }

        return new NotificationResource(null);
    }
}
