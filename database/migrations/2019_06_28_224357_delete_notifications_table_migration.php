<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteNotificationsTableMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            Schema::dropIfExists('notifications');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("user_id")->nullable(); // user creating the notification (usually going to be an admin)
            $table->string("notification_body")->nullable(); // notification text
            $table->timestamps();
        });
    }
}
