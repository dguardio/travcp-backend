<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string("company");
            $table->string("address");
            $table->string("city");
            $table->string("country");
            $table->integer("postal_code");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("company");
            $table->dropColumn("address");
            $table->dropColumn("city");
            $table->dropColumn("country");
            $table->dropColumn("postal_code");
        });
    }
}
