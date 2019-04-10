<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bookable_type_id')->nullable()->unsigned();
            $table->string('title');
            $table->string('slug')->unique();
            $table->integer('merchant_id')->unsigned();
            $table->text('about_merchant')->nullable();
            $table->string('location')->nullable();
            
            // $table->string('language')->nullable();
            $table->text('description')->nullable();
            $table->string('contact_email');
            // $table->float('naira_price', 12, 2)->default(0);
            // $table->float('dollar_price', 12, 2)->default(0);
            // $table->float('pounds_price', 12, 2)->default(0);
            // $table->string('meetup_location')->nullable();
            
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
        Schema::dropIfExists('events');
    }
}
