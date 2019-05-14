<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $table = "uploads";

    public function merchant_extra(){
        return $this->belongsTo("App\MerchantExtra");
    }
}
