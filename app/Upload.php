<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $table = "uploads";

    public function merchant_extra(){
        return $this->belongsTo("App\MerchantExtra");
    }

    public function experience(){
        return $this->belongsTo("App\Experience");
    }
}
