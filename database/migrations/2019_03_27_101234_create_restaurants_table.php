<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bookable_type_id')->nullable()->unsigned();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('merchant_id')->unsigned();
            $table->string('location')->nullable();
            $table->float('price_from', 10, 2)->default(0);
            $table->float('price_to', 10, 2)->default(0);
            $table->text('extra_perks')->nullable();
            $table->text('drink_types')->nullable();
            $table->text('dining_options')->nullable();
            $table->boolean('has_outdoor_sitting')->default(false);
            $table->text('opening_and_closing_hours')->nullable();
            $table->text('cancellation_policy')->nullable();
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
        Schema::dropIfExists('restaurants');
    }
}
