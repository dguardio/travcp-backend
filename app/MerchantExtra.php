<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantExtra extends Model
{
    public $additional_attributes = ['approved'];

    protected $table = "merchant_extras";

    protected $guarded = [];

    public function upload(){
        return $this->hasOne("App\Upload");
    }

    public function user(){
        return $this->belongsTo("App\User");
    }

    public function getApprovedAttribute()
    {
        $approved = false;
        if(!is_null($this->user)){
            $approved = $this->user->approved;
        }
        return $approved;
    }
}
