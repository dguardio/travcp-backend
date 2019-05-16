<?php
/**
 * Created by PhpStorm.
 * User: TheDarkKid
 * Date: 5/14/2019
 * Time: 3:08 PM
 */

namespace App\Traits;


use Sdkcodes\LaraPaystack\PaystackService;

trait Payments
{

//    public function verifyTransaction(PaystackService $paystackService, $transactionId, $price){
//        // try to verify transaction
//        try{
//            $paystackService->verifyTransaction($transactionId);
//        }
//        catch(\Exception $ex){
//
//            $errors = ["Transaction is invalid"];
//            return response(['errors'=> $errors], 419);
//        }
//
//        $paymentData = $paystackService->getPaymentData();
//        if($paymentData->amount != $price){
//            $errors = ["amount billed in transaction differs from sent amount"];
//            return response(['errors'=> $errors], 419);
//        }
//        return true;
//    }

    public function verifyTransaction(PaystackService $paystackService, $transactionId, $price){
        return true;
    }
}