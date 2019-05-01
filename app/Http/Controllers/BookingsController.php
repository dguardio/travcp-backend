<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Http\Requests\Bookings\BookingsStoreRequest;
use App\Http\Requests\Bookings\BookingsUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Resources\Booking as BookingResource;
class BookingsController extends Controller
{


    /**
     * Display a listing of the all bookings with pagination.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        // get all bookings from latest to oldest
        $bookings = Booking::orderBy('id', 'DESC')->paginate(10);

        // return bookings as a resource
        return BookingResource::collection($bookings);
    }


    /**
     * Store a newly created booking in storage.
     *
     * @param BookingsStoreRequest $request
     * @return BookingResource
     */
    public function store(BookingsStoreRequest $request)
    {
        // return validated data and throw error if there is one
        $validated = $request->validated();

        // create new booking object from validated data
        $booking = new Booking($validated);

        // save booking if all is well
        if($booking->save()){
            return new BookingResource($booking);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to create booking'];
        return response(['errors'=> $errors], 500);
    }


    /**
     * Display the specified booking by id.
     *
     * @param  int  $id
     * @return BookingResource
     */
    public function show($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single booking
        try{
            $booking = Booking::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["merchant payment not found"];
            return response(['errors'=> $errors], 404);
        }

        //return single booking as a resource
        return new BookingResource($booking);
    }


    /**
     * get all bookings owned by a merchant using the id
     * @param $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getBookingsByMerchantId($id){
        // get all bookings owned by merchant with that id
        $bookings = Booking::where('merchant_id', $id)->orderBy('id', 'DESC')->paginate(10);

        // return booking as a resource
        return BookingResource::collection($bookings);
    }


    /**
     * Update the specified booking in storage.
     *
     * @param BookingsUpdateRequest $request
     * @param  int $id
     * @return BookingResource
     */
    public function update(BookingsUpdateRequest $request, $id)
    {
        // create booking object
        $booking =  Booking::findOrFail($id);

        // validate request and return validated data
        $validated = $request->validated();

        //add other booking object properties
        $booking->update($validated);

        //save merchant payment if transaction goes well
        if($booking->save()){
            return new BookingResource($booking);
        }

        // return error if transaction not successful
        $errors = ['unknown error occurred while trying to update booking'];
        return response(['errors'=> $errors], 500);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return BookingResource
     */
    public function destroy($id)
    {
        //validate data before putting in
        $id = trim(htmlspecialchars($id));

        if(!is_numeric($id)){
            $errors = ["id is not a number"];
            return response(['errors'=> $errors], 422);
        }

        //try to get a single booking
        try{
            $booking = Booking::findOrFail($id);
        }catch (ModelNotFoundException $e){
            $errors = ["merchant payment entry not found"];
            return response(['errors'=> $errors], 404);
        }

        // delete booking
        if($booking->delete()){
            return new BookingResource($booking);
        }

        $errors = ['unknown error occurred while trying to delete booking'];
        return response(['errors'=> $errors], 500);
    }
}
