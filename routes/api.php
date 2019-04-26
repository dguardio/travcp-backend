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

Route::group(['middleware' => 'api',
    'prefix' => 'auth'], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('register', "AuthController@register");
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');

});

Route::post('auth/forgot', "PasswordResetController@forgot");

Route::get('experiences', "ExperienceController@list");
Route::get('experiences/{id}', "ExperienceController@show");

// Route::get();
Route::get('events', "EventController@list");

Route::get('restaurants', "RestaurantController@list");
Route::get('restaurants/{id}', "RestaurantController@show");
Route::get('restaurants/{id}/menu', "FoodMenuController@list");
Route::group(['middleware' => ['api', 'auth:api']], function(){
    Route::post('bookings/experiences/{id}', "BookingController@bookExperience");    
    Route::post('bookings/events/{id}', "BookingController@bookEvent");
});