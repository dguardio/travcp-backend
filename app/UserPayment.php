<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPayment extends Model
{
    // for the currency column, possible currencies are: naira, dollar, pound

    protected $table = "user_payments";

    protected $guarded = [];

}
