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
Route::get('merchants/{id}/experiences', 'ExperiencesController@getExperienceByMerchantId'); // get merchant experiences
Route::get('merchants/{id}/payments', 'MerchantPaymentsController@getMerchantPaymentsByMerchantId'); // get merchant payments
Route::get('merchants/{id}/reviews', 'ReviewsController@getReviewsByMerchantId'); // get all merchant reviews
Route::get('merchants/{id}/bookings', 'BookingsController@getBookingsByMerchantId'); // get all merchant bookings
Route::get('merchants/{id}/extras', 'MerchantExtrasController@getMerchantExtrasByMerchantId'); // get merchant extras

/** Users **/
Route::get('users/', 'UsersController@index'); // get all users
Route::get('users/{id}', 'UsersController@show'); // get a single user
Route::get('users/role/{role}', 'UsersController@getUsersByRole'); // get a single user
Route::get('users/{id}/bookings', 'BookingsController@getBookingByUserId'); // get all bookings made by a particular user

/** Experiences **/
Route::get('experiences/', 'ExperiencesController@index'); // get all experiences
Route::get('experiences/{id}', 'ExperiencesController@show'); // get a single experience
Route::get('experience_types/{id}/experiences', 'ExperiencesController@getExperiencesByTypesId');

/** Merchant Payments**/
Route::get('payments/merchants/', 'MerchantPaymentsController@index'); // get all merchant payments
Route::get('payments/merchants/{id}', 'MerchantPaymentsController@show'); // get a single merchant payment

/** Notifications **/
Route::get('notifications/', 'NotificationsController@index'); // get all notifications
Route::get('notifications/{id}', 'NotificationsController@show'); // get a single notification

/** Experience Types **/
Route::get('experience_types/{id}', 'ExperienceTypesController@show'); // get a single experience type
Route::get('experience_types/', 'ExperienceTypesController@index'); // get all experiences types
Route::get('experience_types/name/{name}', 'ExperienceTypesController@getExperienceTypeByName'); // get a single experience type by its name

/** Experience Type Categories **/
Route::get('experience_types_categories', 'ExperienceTypesCategoriesController@index'); // get all categories
Route::get('experience_types/{id}/categories', 'ExperienceTypesCategoriesController@getCategoryByExperienceTypeId'); // get categories by experience type id
Route::get('experience_types_categories/{id}', 'ExperienceTypesCategoriesController@show'); // get a single category

/** Reviews **/
Route::get('reviews/', 'ReviewsController@index'); // get all reviews
Route::get('reviews/{id}', 'ReviewsController@show'); // get a single reviews

/** Merchant Extras**/
Route::get('merchant/extras/', 'MerchantExtrasController@index'); // get all merchant extras
Route::get('merchant/extras/{id}', 'MerchantExtrasController@show'); // get a single merchant extras entry by id

/** Bookings **/
Route::get('bookings/', 'BookingsController@index'); // get all bookings
Route::get('bookings/{id}', 'BookingsController@show'); // get a single booking

/** uploads **/
Route::get('uploads/', 'UploadsController@index'); // get all uploads
Route::get('uploads/{id}', 'UploadsController@show'); // get a single upload

/** User Payments **/
Route::get('payments/users/', 'UserPaymentsController@index'); // get all user payment entries
Route::get('payments/users/{id}', 'UserPaymentsController@show'); // get a single user payment entry

/**Misc - not yet sorted**/
Route::get('events', "EventController@list");
Route::get('restaurants', "RestaurantController@list");
Route::get('restaurants/{id}', "RestaurantController@show");
Route::get('restaurants/{id}/menu', "FoodMenuController@list");


Route::group(['middleware' => ['api', 'auth:api']], function(){

    /** Merchants **/
    Route::put('merchants/{id}/extras', 'MerchantExtrasController@updateByMerchantId'); // update merchant extras using merchant id

    /** Users **/
    Route::put('users/{id}', 'UsersController@update'); // update an existing user

    /** Merchant Payments**/
    Route::post('payments/merchants/', 'MerchantPaymentsController@store'); // create new merchant payment
    Route::put('payments/merchants/{id}', 'MerchantPaymentsController@update'); // update an existing merchant payment
//    Route::delete('payments/merchants/{id}', 'MerchantPaymentsController@destroy'); // delete a particular merchant payment

    /** Notifications **/
    Route::post('notifications/', 'NotificationsController@store'); // create new notification
    Route::put('notifications/{id}', 'NotificationsController@update'); // update an existing notification
//    Route::delete('notifications/{id}', 'NotificationsController@destroy'); // delete a particular notification

    /** Experiences **/
    Route::post('experiences/', 'ExperiencesController@store'); // create new experience
    Route::put('experiences/{id}', 'ExperiencesController@update'); // update an existing experience
    Route::delete('experiences/{id}', 'ExperiencesController@destroy'); // delete a particular experience

    /** Experience Types **/
    Route::put('experience_types/{id}', 'ExperienceTypesController@update'); // update an existing experience type
    Route::post('experience_types/', 'ExperienceTypesController@store'); // create new experience type

//    Route::delete('experience_types/{id}', 'ExperienceTypesController@destroy'); // delete a particular experience

    /** Experience Type Categories **/
    Route::post('experience_types_categories', 'ExperienceTypesCategoriesController@store'); // create new category
    Route::put('experience_types_categories/{id}', 'ExperienceTypesCategoriesController@update'); // update an existing category
//    Route::delete('experience_types_categories/{id}', 'ExperienceTypesCategoriesController@destroy'); // delete a particular category

    /** Reviews **/
    Route::put('reviews/{id}', 'ReviewsController@update'); // update an existing review
    Route::delete('reviews/{id}', 'ReviewsController@destroy'); // delete a particular review
    Route::post('reviews/', 'ReviewsController@store'); // create new review

    /** Bookings **/
    Route::post('bookings/', 'BookingsController@store'); // create new booking
    Route::put('bookings/{id}', 'BookingsController@update'); // update an existing booking
    Route::delete('bookings/{id}', 'BookingsController@destroy'); // delete a particular booking

    /** Merchant Extras **/
    Route::post('merchant/extras/', 'MerchantExtrasController@store'); // store a new merchant extras entry
    Route::put('merchant/extras/{id}', 'MerchantExtrasController@update'); // store a new merchant extras entry
    Route::delete('merchant/extras/{id}', 'MerchantExtrasController@delete'); // delete a new merchant extras entry

    /** User Payments **/
    Route::post('payments/users/', 'UserPaymentsController@store'); // create new user payment
    Route::put('payments/users/{id}', 'UserPaymentsController@update'); // update an existing user payment entry
//    Route::delete('payments/users/{id}', 'UserPaymentsController@destroy'); // delete a particular user payment

    /**Misc - not yet sorted**/
    Route::post('bookings/experiences/{id}', "BookingController@bookExperience");
    Route::post('bookings/events/{id}', "BookingController@bookEvent");

//    Route::get('experiences', "ExperienceController@list");
//    Route::get('experiences/{id}', "ExperienceController@show");
//    Route::delete('users/{id}', 'UsersController@destroy'); // delete a particular user payment
});

