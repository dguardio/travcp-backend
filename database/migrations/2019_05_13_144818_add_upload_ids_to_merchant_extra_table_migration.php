<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUploadIdsToMerchantExtraTableMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_extras', function (Blueprint $table) {
            $table->dropColumn("identity_document_file");
            $table->integer("upload_id")->nullable();
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
            $table->dropColumn("upload_id");
            $table->integer("identity_document_file");
        });
    }
}
