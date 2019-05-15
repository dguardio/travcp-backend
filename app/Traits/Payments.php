<?php
/**
 * Created by PhpStorm.
 * User: TheDarkKid
 * Date: 5/14/2019
 * Time: 3:08 PM
 */

namespace App\Traits;


trait Payments
{

    public function verifyTransaction($transactionId, $price){
        return true;
    }
}