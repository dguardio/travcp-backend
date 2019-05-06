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

/** Merchants **/
Route::get('merchants/{id}/profile', 'MerchantController@profile'); // get merchant profile
Route::get('merchants/{id}/experiences', 'ExperiencesController@getExperienceByMerchantId'); // get merchant experiences
Route::get('merchants/{id}/payments', 'MerchantPaymentsController@getMerchantPaymentsByMerchantId'); // get merchant payments
Route::get('merchants/{id}/reviews', 'ReviewsController@getReviewsByMerchantId'); // get all merchant reviews
Route::get('merchants/{id}/bookings', 'BookingsController@getBookingsByMerchantId'); // get all merchant bookings

/** Users **/
Route::get('users/', 'UsersController@index'); // get all users
Route::get('users/{id}', 'UsersController@show'); // get a single user
Route::get('users/role/{role}', 'UsersController@getUsersByRole'); // get a single user

/** Experiences **/
Route::get('experiences/', 'ExperiencesController@index'); // get all experiences
Route::get('experiences/{id}', 'ExperiencesController@show'); // get a single experience
Route::get('experiences/types/{id}/experiences', 'ExperiencesController@getExperiencesByTypesId');

Route::group(['middleware' => ['api', 'auth:api']], function(){

    /** Merchants **/
    Route::put('merchants/{id}/update', 'MerchantController@update'); // update merchant

    /** Users **/
    Route::put('users/{id}', 'UsersController@update'); // update an existing user

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

    /** Experiences **/
    Route::post('experiences/', 'ExperiencesController@store'); // create new experience
    Route::put('experiences/{id}', 'ExperiencesController@update'); // update an existing experience
    Route::delete('experiences/{id}', 'ExperiencesController@destroy'); // delete a particular experience

    /** Experience Types **/
    Route::get('experiences/types/', 'ExperienceTypesController@index'); // get all experiences types
    Route::get('experiences/types/{id}', 'ExperienceTypesController@show'); // get a single experience type
    Route::post('experiences/types/', 'ExperienceTypesController@store'); // create new experience type
    Route::put('experiences/types/{id}', 'ExperienceTypesController@update'); // update an existing experience type
    Route::delete('experiences/types/{id}', 'ExperienceTypesController@destroy'); // delete a particular experience

    /** Experience Type Categories **/
    Route::get('experiences/types/categories', 'ExperienceTypesCategoriesController@index'); // get all categories
    Route::get('experiences/types/{id}/categories', 'ExperienceTypesCategoriesController@getCategoryByExperienceTypeId'); // get categories by experience type id
    Route::get('experiences/types/categories/{id}', 'ExperienceTypesCategoriesController@show'); // get a single category
    Route::post('experiences/types/categories', 'ExperienceTypesCategoriesController@store'); // create new category
    Route::put('experiences/types/categories/{id}', 'ExperienceTypesCategoriesController@update'); // update an existing category
    Route::delete('experiences/types/categories/{id}', 'ExperienceTypesCategoriesController@destroy'); // delete a particular category

    /** Reviews **/
    Route::get('reviews/', 'ReviewsController@index'); // get all reviews
    Route::get('reviews/{id}', 'ReviewsController@show'); // get a single reviews
    Route::post('reviews/', 'ReviewsController@store'); // create new review
    Route::put('reviews/{id}', 'ReviewsController@update'); // update an existing review
    Route::delete('reviews/{id}', 'ReviewsController@destroy'); // delete a particular review

    /** Experiences **/
    Route::get('experiences/', 'ExperiencesController@index'); // get all experiences
    Route::get('experiences/{id}', 'ExperiencesController@show'); // get a single experience
    Route::post('experiences/', 'ExperiencesController@store'); // create new experience
    Route::put('experiences/{id}', 'ExperiencesController@update'); // update an existing experience
    Route::delete('experiences/{id}', 'ExperiencesController@destroy'); // delete a particular experience

    /** Bookings **/
    Route::get('bookings/', 'BookingsController@index'); // get all bookings
    Route::get('bookings/{id}', 'BookingsController@show'); // get a single booking
    Route::post('bookings/', 'BookingsController@store'); // create new booking
    Route::put('bookings/{id}', 'BookingsController@update'); // update an existing booking
    Route::delete('bookings/{id}', 'BookingsController@destroy'); // delete a particular booking

    /** User Payments **/
    Route::get('payments/users/', 'UserPaymentsController@index'); // get all user payment entries
    Route::get('payments/users/{id}', 'UserPaymentsController@show'); // get a single user payment entry
    Route::post('payments/users/', 'UserPaymentsController@store'); // create new user payment
    Route::put('payments/users/{id}', 'UserPaymentsController@update'); // update an existing user payment entry
    Route::delete('payments/users/{id}', 'UserPaymentsController@destroy'); // delete a particular user payment

    /**Misc - not yet sorted**/
    Route::get('events', "EventController@list");
    Route::get('restaurants', "RestaurantController@list");
    Route::get('restaurants/{id}', "RestaurantController@show");
    Route::get('restaurants/{id}/menu', "FoodMenuController@list");
    Route::post('bookings/experiences/{id}', "BookingController@bookExperience");
    Route::post('bookings/events/{id}', "BookingController@bookEvent");

//    Route::get('experiences', "ExperienceController@list");
//    Route::get('experiences/{id}', "ExperienceController@show");
//    Route::delete('users/{id}', 'UsersController@destroy'); // delete a particular user payment
});

