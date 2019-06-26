<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('message');
            $table->integer('user_id');
            $table->integer('conversation_id');
            $table->boolean("deleted_by_sender")->default(0);
            $table->boolean("deleted_by_receiver")->default(0);
            $table->dateTime('read_at')->nullable();
            $table->string('type')->default('text')->comment("image, video, text, document");
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
        Schema::dropIfExists('messages');
    }
}
