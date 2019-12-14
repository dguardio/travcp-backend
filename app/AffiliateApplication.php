<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AffiliateApplication extends Model
{
    protected $table = "affiliate_applications";
    protected $fillable = [ 'user_id' ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userId()
    {
        return $this->belongsTo(User::class, 'user_id');
    } 
}
