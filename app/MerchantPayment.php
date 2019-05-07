<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantPayment extends Model
{
    // for the currency column, possible currencies are: naira, dollar, pound
    protected $table = "merchant_payments";

    protected $guarded = [];


}
