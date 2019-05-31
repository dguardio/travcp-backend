<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeMerchantExtrasTableColumnsLongerMerchantExtrasTableMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_extras', function (Blueprint $table) {
            $table->text("business_name")->change();
            $table->text("business_email")->change();
            $table->text("bio")->change();
            $table->text("phone")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_extras', function (Blueprint $table) {
            $table->string("business_name")->change();
            $table->string("business_email")->change();
            $table->string("bio")->change();
            $table->string("phone")->change();
        });
    }
}
