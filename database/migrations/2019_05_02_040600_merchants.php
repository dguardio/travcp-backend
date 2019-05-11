<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Merchants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->increments('id');
            $table->text('about_merchant')->nullable();
            $table->string('contact_email');
            $table->string('location')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();//pend
            $table->text('offerings')->nullable();
            $table->text('ratings')->nullable();
            $table->text('picture_url')->nullable();
            $table->string('language')->nullable();
            $table->text('description')->nullable();
            $table->float('naira_price', 12, 2)->default(0);
            $table->float('dollar_price', 12, 2)->default(0);
            $table->float('pounds_price', 12, 2)->default(0);
            $table->string('meetup_location')->nullable();
            $table->float('price_from', 10, 2)->default(0);
            $table->float('price_to', 10, 2)->default(0);
            $table->text('itenary')->nullable();
            $table->text('reviews')->nullable();//reviews
            $table->text('tourist_expected_items')->nullable()->comment("Items tourist should bring along");
            $table->integer('number_admittable')->nullable()->default(0);
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
        Schema::dropIfExists('merchants');
    }
}
