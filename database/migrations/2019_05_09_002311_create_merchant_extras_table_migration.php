<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantExtrasTableMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_extras', function (Blueprint $table) {
            $table->increments('id');
            $table->string("business_name")->nullable();
            $table->string("business_email")->nullable();
            $table->string("identity_document_file")->nullble();
            $table->string("bio")->nullable();
            $table->string("phone")->nullable();
            $table->integer("merchant_id");
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
        Schema::dropIfExists('merchant_extras');
    }
}
