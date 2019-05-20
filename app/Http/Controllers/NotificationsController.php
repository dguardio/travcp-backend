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
     * Display a listing of all db notifications with pagination.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        // get notifications
        $notifications = Notification::orderBy('id', 'DESC')->paginate(20);

        // return collection of notifications as a resource
        return NotificationResource::collection($notifications);
    }


    /**
     * Store a newly created notification in storage.
     *
     * @param NotificationsStoreRequest $request
     * @return NotificationResource
     */
    public function store(NotificationsStoreRequest $request)
    {
        // validate request and return validated data
        $validated = $request->validated();

        // create notification object and add other notification object properties
        $notification =  new Notification($validated);

        // save notification if transaction goes well
        if($notification->save()){
            return new NotificationResource($notification);
        }

        return new NotificationResource(null);
    }

    /**
     * Display the specified notification.
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
     * Update the specified notification in storage.
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
        $notification->update($validated);

        // save notification if transaction goes well
        if($notification->save()){
            return new NotificationResource($notification);
        }

        return new NotificationResource(null);
    }

    /**
     * Remove the specified notification from storage.
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
