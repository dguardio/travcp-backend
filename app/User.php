<?php

namespace App;

use App\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends \TCG\Voyager\Models\User implements JWTSubject
{
    use Notifiable;

    public $additional_attributes = ['merchant_name'];

    /**
     * listed with events listeners for each time a model is created or saved.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            $model->name = $model->first_name . ' ' . $model->surname;
        });

        static::updating (function($model)
        {
            $model->name = $model->first_name . ' ' . $model->surname;
        });
    }

    /**
     * @return mixed
     */
    public function getMerchantNameAttribute()
    {
        return $this->merchant_extra()->business_name;
    }

    /**
     * get users list by adding specific parameters
     * @param Request $request
     * @return mixed
     */
    public static function getBySearch(Request $request){
        return self::when($request->city, function($query) use($request){
            return $query->where('city', "LIKE", "%{$request->city}%");
        })->when($request->country, function($query) use($request){
            return $query->where('country', "LIKE", "%{$request->country}%");
        })->when($request->address, function($query) use($request){
            return $query->where('address', "LIKE", "%{$request->address}%");
        })->when($request->subscribed_to_newsletter, function($query)  use($request){
            $subscribed_to_newsletter = $request->subscribed_to_newsletter ? true: false;
            return $query->where('subscribed_to_newsletter', '=', $subscribed_to_newsletter);
        })->when($request->cities, function($query)  use($request){
            return $query->whereIn('city', $request->cities);
        })->when($request->signed_in, function($query)  use($request){
            $signed_in = $request->signed_in ? true: false;
            return $query->where('signed_in', '=', $signed_in);
        });
    }


    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * send password reset notification to user
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }


    /**
     * get experiences relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function experiences(){
        return $this->hasMany('App\Experience', 'merchant_id');
    }

    /**
     * get reviews relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews(){
        return $this->hasMany('App\Review', 'user_id');
    }

    /**
     * get cart relation
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cart(){
        return $this->hasOne("App\Cart");
    }

    /**
     * get upload relation
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function upload(){
        return $this->hasOne("App\Upload");
    }

    /**
     * get merchant extra relation
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function merchant_extra(){
        return $this->hasOne("App\MerchantExtra");
    }

    /**
     *  get favourite relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favourites(){
        return $this->hasMany(Favourite::class, 'user_id');
    }
}
