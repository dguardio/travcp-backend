<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**Authentication**/
Route::group(['middleware' => 'api',
    'prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('register', "AuthController@register");
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');
});
Route::post('auth/forgot', "PasswordResetController@forgot");

Route::group(['middleware' => ['api', 'auth:api']], function(){
    /***** Merchant Endpoints ******/
    /** Merchant Payments**/
    Route::get('payments/merchants/', 'MerchantPaymentsController@index'); // get all merchant payments
    Route::get('payments/merchants/{id}', 'MerchantPaymentsController@show'); // get a single merchant payment
    Route::post('payments/merchants/', 'MerchantPaymentsController@store'); // create new merchant payment
    Route::put('payments/merchants/{id}', 'MerchantPaymentsController@update'); // update an existing merchant payment
    Route::delete('payments/merchants/{id}', 'MerchantPaymentsController@destroy'); // delete a particular merchant payment

    /** Notifications **/
    Route::get('notifications/', 'NotificationsController@index'); // get all notifications
    Route::get('notifications/{id}', 'NotificationsController@show'); // get a single notification
    Route::post('notifications/', 'NotificationsController@store'); // create new notification
    Route::put('notifications/{id}', 'NotificationsController@update'); // update an existing notification
    Route::delete('notifications/{id}', 'NotificationsController@destroy'); // delete a particular notification

    /** Reviews **/
    Route::get('reviews/', 'ReviewsController@index'); // get all reviews
    Route::get('reviews/{id}', 'ReviewsController@show'); // get a single reviews
    Route::post('reviews/', 'ReviewsController@store'); // create new review
    Route::put('reviews/{id}', 'ReviewsController@update'); // update an existing review
    Route::delete('reviews/{id}', 'ReviewsController@destroy'); // delete a particular review

    /**Misc - not yet sorted**/
    Route::get('experiences', "ExperienceController@list");
    Route::get('experiences/{id}', "ExperienceController@show");

    Route::get('events', "EventController@list");

    Route::get('restaurants', "RestaurantController@list");
    Route::get('restaurants/{id}', "RestaurantController@show");
    Route::get('restaurants/{id}/menu', "FoodMenuController@list");
    Route::post('bookings/experiences/{id}', "BookingController@bookExperience");
    Route::post('bookings/events/{id}', "BookingController@bookEvent");

    /***** User Endpoints ******/
    /** User Payments **/
    Route::get('payments/users/', 'UserPaymentsController@index'); // get all user payment entries
    Route::get('payments/users/{id}', 'UserPaymentsController@show'); // get a single user payment entry
    Route::post('payments/users/', 'UserPaymentsController@store'); // create new user payment
    Route::put('payments/users/{id}', 'UserPaymentsController@update'); // update an existing user payment entry
    Route::delete('payments/users/{id}', 'UserPaymentsController@destroy'); // delete a particular user payment
});


