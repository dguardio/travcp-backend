<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantPaymentTableMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string("description");
            $table->integer("payer_id"); // id of the admin making the payment
            $table->integer("merchant_id"); // id of the merchant recieving the payment
            $table->double("amount"); // amount paid to the merchant
            $table->string("currency"); //currency of payment; naira, dollar, pounds
            $table->string("transaction_id"); // transaction id gotten from the payment gateway
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchant_payments');
    }
}
