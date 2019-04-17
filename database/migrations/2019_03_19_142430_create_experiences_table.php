<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bookable_type_id')->nullable()->unsigned();
            $table->string('title');
            $table->string('slug')->unique();
            $table->integer('merchant_id')->unsigned();
            $table->text('about_merchant')->nullable();
            $table->string('contact_email');
            $table->string('location')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->text('offerings')->nullable();
            $table->string('language')->nullable();
            $table->text('description')->nullable();
            $table->float('naira_price', 12, 2)->default(0);
            $table->float('dollar_price', 12, 2)->default(0);
            $table->float('pounds_price', 12, 2)->default(0);
            $table->string('meetup_location')->nullable();
            $table->float('price_from', 10, 2)->default(0);
            $table->float('price_to', 10, 2)->default(0);
            $table->text('itenary')->nullable();
            $table->text('extra_perks')->nullable();
            $table->text('drink_types')->nullable();
            $table->text('dining_options')->nullable();
            $table->boolean('has_outdoor_sitting')->default(false);
            $table->text('opening_and_closing_hours')->nullable();
            $table->text('cancellation_policy')->nullable();
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
        Schema::dropIfExists('experiences');
    }
}
