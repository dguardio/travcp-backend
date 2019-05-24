<?php

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect("http://travvapp.herokuapp.com");
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('travvforum', function(){

});
    
Route::get('travvforum', function(Request $request){
    
    try {
        JWTAuth::parseToken();
        $token = JWTAuth::getToken();
    } catch (\Throwable $th) {
        // abort(419, "No valid token");
    }
    try {
        $request->user = JWTAuth::authenticate($token);
        Auth::login($request->user, true);
        
        return redirect(url('forums'));
    } catch (\Throwable $th) {
        //throw $th;
    }
    
    return redirect(url('forums'));
});
    