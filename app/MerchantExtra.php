<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantExtra extends Model
{
    protected $table = "merchant_extras";

    protected $guarded = [];

    public function upload(){
        return $this->hasOne("App\Upload");
    }

    public function user(){
        return $this->belongsTo("App\User");
    }
}
