<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $table = "uploads";

    protected $guarded = [];

    public function merchant_extra(){
        return $this->belongsTo("App\MerchantExtra");
    }

    public function experience(){
        return $this->belongsTo("App\Experience");
    }

    public function user(){
        return $this->belongsTo("App\User");
    }
}
