<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReUpdateBookingsTableMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn("bookable_type_id");
            $table->dropColumn("bookable_type_name");
            $table->dropColumn("bookable_id");
            $table->dropColumn("bookable_name");
            $table->integer("experience_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer("bookable_type_id");
            $table->string("bookable_type_name");
            $table->integer("bookable_id");
            $table->string("bookable_id");
            $table->dropColumn("experience_id");
        });
    }
}
