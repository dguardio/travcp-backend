<?php

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

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

Route::post('mail/career', 'CareersController@sendMail');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();

});


/**Authentication**/

Route::group(['middleware' => ['api', 'forceJson'],
    'prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('register', "AuthController@register");
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');

    Route::post('forgot', "PasswordResetController@forgot");
    Route::post('reset', "PasswordResetController@doReset");

    Route::post('verify', 'AuthController@verifyUser');
    Route::post('resend_verification', 'AuthController@resendVerificationMail');

    Route::get('me', 'AuthController@me');
});

Route::group(['middleware' => 'api'], function($router){

    Route::get('forums', function(Request $request){
        
        
        try {
            JWTAuth::parseToken();
            $token = JWTAuth::getToken();
        } catch (\Throwable $th) {
            abort("No valid token");
        }
        try {
            $request->user = JWTAuth::authenticate($token);
            Auth::guard('web')->login($request->user, true);
            
            return redirect(url('forums'));
        } catch (\Throwable $th) {
            //throw $th;
        }
        
        return redirect(url('forums'));
    });
    
});

// HOMEPAGE FEATURED ITEMS
Route::get('/experiences/featured', 'ExperiencesController@homepage_featured_experiences');
Route::get('/videos/featured', 'VideoController@homepage_featured_videos');

Route::group(['middleware' => ['api', 'forceJson']], function(){

    /** Merchants **/
    Route::get('merchants/{id}/experiences', 'ExperiencesController@getExperienceByMerchantId'); // get merchant experiences
    Route::get('merchants/{id}/payments', 'MerchantPaymentsController@getMerchantPaymentsByMerchantId'); // get merchant payments
    Route::get('merchants/{id}/reviews', 'ReviewsController@getReviewsByMerchantId'); // get all merchant reviews
    Route::get('merchants/{id}/bookings', 'BookingsController@getBookingsByMerchantId'); // get all merchant bookings
    Route::get('merchants/{id}/extras', 'MerchantExtrasController@getMerchantExtrasByMerchantId'); // get merchant extras
    Route::get('merchant_extras/{user_id}', 'MerchantExtrasController@getByUserId');

    /** Users **/
    Route::get('users/', 'UsersController@index'); // get all users
    Route::get('users/{id}', 'UsersController@show'); // get a single user
    Route::get('users/role/{role}', 'UsersController@getUsersByRole'); // get a single user by role
    Route::get('users/{id}/bookings', 'BookingsController@getBookingByUserId'); // get all bookings made by a particular user
    Route::get('users/{id}/cart', 'CartsController@getUserCart'); // get the cart of a particular user
    Route::get('users/{id}/orders', 'OrdersController@getAllOrdersByUserId'); // get all orders of a particular user

    /** Experiences **/
    Route::get('experiences/', 'ExperiencesController@index'); // get all experiences
    Route::get('experiences/random/{limit}', 'ExperiencesController@getRandom'); // get all experiences
    Route::get('experiences/{id}', 'ExperiencesController@show'); // get a single experience
    Route::get('experience_types/{id}/experiences', 'ExperiencesController@getExperiencesByTypesId');

    /** Merchant Payments**/
    Route::get('payments/merchants/', 'MerchantPaymentsController@index'); // get all merchant payments
    Route::get('payments/merchants/{id}', 'MerchantPaymentsController@show'); // get a single merchant payment

    /** Notifications **/
    Route::get('notifications/', 'NotificationsController@index'); // get all notifications
    Route::get('notifications/{id}', 'NotificationsController@show'); // get a single notification

    /** Orders **/
    Route::get('orders/', 'OrdersController@index'); // get all order
    Route::get('orders/{id}', 'OrdersController@show'); // get single order

    /** Order Items **/
    Route::get('order_items', 'OrderItemsController@index'); // get all order items
    Route::get('order_items/{id}', 'OrderItemsController@show'); // get single order items
    Route::get('orders/{id}/items', 'OrderItemsController@getOrderItemsByOrderId'); // get all items in a single order

    /** Cart **/
    Route::middleware('auth:api')->get('/cart','OrderController@getCurrentUserCart' );

    /** Carts **/
    Route::get('carts/', 'CartsController@index'); // get all carts
    Route::get('carts/{id}', 'CartsController@show'); // get single cart

    /** Cart Items **/
    Route::get('cart_items', 'CartItemsController@index'); // get all cart items
    Route::get('cart_items/{id}', 'CartItemsController@show'); // get single cart item

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
    Route::get('experiences/{experience_id}/reviews/rating/{rating}', 'ReviewsController@getExperienceReviewByRating'); // get all an experience reviews with a specific rating
    Route::get('reviews/{id}', 'ReviewsController@show'); // get a single reviews
    Route::get('experiences/{id}/reviews', 'ReviewsController@getReviewsByExperienceId'); // get all reviews with particular experience id

    /** Merchant Extras**/
    Route::get('merchant/extras/', 'MerchantExtrasController@index'); // get all merchant extras
    Route::get('merchant/extras/{id}', 'MerchantExtrasController@show'); // get a single merchant extras entry by id
    Route::get('merchant/extras/users/{id}', 'MerchantExtrasController@getMerchantExtraByUserId'); // get a single merchant extras entry by its user id

    /** Bookings **/
    Route::get('bookings/', 'BookingsController@index'); // get all bookings
    Route::post('bookings/exists', 'BookingsController@checkIfPreviousBookingExists'); // check if booking exists
    Route::get('bookings/{id}', 'BookingsController@show'); // get a single booking

    /** Uploads **/
    Route::get('uploads/', 'UploadsController@index'); // get all uploads
    Route::get('uploads/{id}', 'UploadsController@show'); // get a single upload

    /** Food Menu **/
    Route::get('food/menus/', 'FoodMenusController@index'); // get all food menus
    Route::get('food/menus/{id}', 'FoodMenusController@show'); // get a single food menu
    Route::get('restaurants/{id}/menus', "FoodMenuController@list");

    /** Food Classifications **/
    Route::get('food_classifications', "FoodClassificationController@index");
    Route::get('food_classifications/{id}', "FoodClassificationController@show");

    /** User Payments **/
    Route::get('payments/users/', 'UserPaymentsController@index'); // get all user payment entries
    Route::get('payments/users/{id}', 'UserPaymentsController@show'); // get a single user payment entry
    Route::get('payments/users/transactions/{id}', 'UserPaymentsController@getUserPaymentByTransactionId'); // get a single user payment entry by transaction id

    /** Medals **/
    Route::get('medals/', 'MedalController@index'); // get all medals

    /** Favourites **/
    Route::get('favourites/', 'FavouriteController@index'); // get all favourites

    /** Videos **/
    Route::get('videos/', 'VideoController@index'); // get all videos

    /** Video Categories **/
    Route::get('video_categories/', 'VideoCategoryController@index'); // get all video categories
});

Route::group(['middleware' => ['api', 'auth:api', 'forceJson']], function(){

    /** Cart **/
    Route::post('cart/add/', 'OrderController@addToCart'); // add booking to cart
    Route::post('cart/checkout/', 'OrderController@checkout'); // checkout

    /** Food menu **/
    Route::post('food/menus', 'FoodMenusController@store'); // create a food menu
    Route::put('food/menus/{id}', 'FoodMenusController@update'); // update a food menu
    Route::delete('food/menus/{id}', 'FoodMenusController@destroy'); // delete a food menu

    /** Orders **/
    Route::post('orders/', 'OrdersController@store'); // create new order
    Route::put('orders/{id}', 'OrdersController@update'); // update an existing order
//    Route::delete('orders/{id}', 'OrdersController@destroy'); // delete a particular order

    /** Order Items **/
    Route::post('order_items', 'OrderItemsController@store'); // create new order item
    Route::put('order_items/{id}', 'OrderItemsController@update'); // update an existing order item
    Route::delete('order_items/{id}', 'OrderItemsController@destroy'); // delete a particular order item

    /** Carts **/
    Route::post('carts/', 'CartsController@store'); // create new cart
    Route::put('carts/{id}', 'CartsController@update'); // update an existing cart
//    Route::delete('carts/{id}', 'CartsController@destroy'); // delete a particular cart

    /** Cart Items **/
    Route::post('cart_items', 'CartItemsController@store'); // create new cart item
    Route::put('cart_items/{id}', 'CartItemsController@update'); // update an existing cart item
    Route::delete('cart_items/{id}', 'CartItemsController@destroy'); // delete a particular cart item

    /** Merchants **/
    Route::put('merchants/{id}/extras', 'MerchantExtrasController@updateByMerchantId'); // update merchant extras using merchant id

     /*Merchant Data */
     Route::get('merchants/', 'MerchantController@index'); // get merchant experiences
     Route::post('merchants/', 'MerchantController@register'); // get merchant by id
     Route::put('merchants/{id}', 'MerchantController@update'); // get all merchant 
     Route::delete('merchants', 'MerchantController@deletemerchant'); //delete merchants
 
    /** Users **/
    Route::put('users/{id}', 'UsersController@update'); // update an existing user

    /** Merchant Payments**/
    Route::post('payments/merchants/', 'MerchantPaymentsController@store'); // create new merchant payment
    Route::put('payments/merchants/{id}', 'MerchantPaymentsController@update'); // update an existing merchant payment
//    Route::delete('payments/merchants/{id}', 'MerchantPaymentsController@destroy'); // delete a particular merchant payment

    /** uploads **/
    Route::post('uploads/', 'UploadsController@store'); // create an upload
    Route::put('uploads/{id}', 'UploadsController@update'); // update an upload
    Route::delete('uploads/{id}', 'UploadsController@destroy'); // delete an upload


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
//    Route::post('reviews/', 'ReviewsController@store'); // create new review
    Route::post('reviews/', 'ReviewsController@storeOrUpdate'); // create new review or update old one

    /** Bookings **/
    Route::post('bookings/', 'BookingsController@store'); // create new booking
    Route::put('bookings/{id}', 'BookingsController@update'); // update an existing booking
    Route::delete('bookings/{id}', 'BookingsController@destroy'); // delete a particular booking

    /** Merchant Extras **/
    Route::post('merchant/extras/', 'MerchantExtrasController@store'); // store a new merchant extras entry
    Route::put('merchant/extras/{id}', 'MerchantExtrasController@update'); // store a new merchant extras entry
    Route::delete('merchant/extras/{id}', 'MerchantExtrasController@destroy'); // delete merchant extras entry

    /** User Payments **/
    Route::post('payments/users/', 'UserPaymentsController@store'); // create new user payment
    Route::put('payments/users/{id}', 'UserPaymentsController@update'); // update an existing user payment entry
//    Route::delete('payments/users/{id}', 'UserPaymentsController@destroy'); // delete a particular user payment

    /** Medals **/
    Route::post('medals/', 'MedalController@store'); // store a new medal entry
    Route::put('medals/{id}', 'MedalController@update'); // update an existing medal entry
    Route::delete('medals/{id}', 'MedalController@destroy'); // delete medal entry

    /** Favourites **/
    Route::post('favourites/', 'FavouriteController@store'); // store a new favourite entry
    Route::put('favourites/{id}', 'FavouriteController@update'); // update an existing favourite entry
    Route::delete('favourites/{id}', 'FavouriteController@destroy'); // delete favourite entry

    /** Videos **/
    Route::post('videos/', 'VideoController@store'); // store a new video entry
    Route::put('videos/{id}', 'VideoController@update'); // update an existing video entry
    Route::delete('videos/{id}', 'VideoController@destroy'); // delete a video entry

    /** Video Categories **/
    Route::post('video_categories/', 'VideoCategoryController@store'); // store a new video category entry
    Route::put('video_categories/{id}', 'VideoCategoryController@update'); // update an existing video category entry
    Route::delete('video_categories/{id}', 'VideoCategoryController@destroy'); // delete a video category entry

    /** Messaging */
    Route::get('conversations', "ChatMessageController@getConversators");
    Route::get('messages', "ChatMessageController@fetchmessages");
    Route::get('/unreadmessagescount', "ChatMessageController@getUnreadMessagesCount");
    Route::get('/last-messages', "ChatMessageController@fetchLastMessages");
    Route::post('messages', "ChatMessageController@store");
});

///**Misc - not yet sorted**/
//Route::get('events', "EventController@list");
//Route::get('restaurants', "RestaurantController@list");
//Route::get('restaurants/{id}', "RestaurantController@show");
//Route::post('bookings/experiences/{id}', "BookingController@bookExperience");
//Route::post('bookings/events/{id}', "BookingController@bookEvent");

// AFFILIATE APPLICATIONS
Route::post('/affiliate_application', 'AffiliateApplicationsController@store');
Route::get('affiliate_application/by_user/{user_id}', 'AffiliateApplicationsController@getByUserId');

Route::get('/experiences/fully_booked/{experience_id}', 'ExperiencesController@experienceFullyBooked');