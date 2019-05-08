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
            $table->string("company")->nullable();
            $table->string("address")->nullable();
            $table->string("city")->nullable();
            $table->string("country")->nullable();
            $table->integer("postal_code")->nullable();
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
