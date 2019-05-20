<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameMerhchantIdToUserIdInMerchantExtrasTableMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_extras', function (Blueprint $table) {
            $table->renameColumn('merchant_id', 'user_id');
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
            $table->renameColumn('user_id', 'merchant_id');
        });
    }
}
