<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypesCategoriesToExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('experiences', function (Blueprint $table) {
            //
            $table->boolean('is_private')->nullable()->default(false);
            $table->integer('age_limits')->nullable();
            $table->integer('duration')->nullable();
            $table->longText('activities')->nullable();
            $table->longText('other_branches')->nullable();
            $table->string('destination_category')->nullable();
            $table->string('tour_category')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('experiences', function (Blueprint $table) {
            //
            $table->dropColumn('is_private');
            $table->dropColumn('age_limits');
            $table->dropColumn('duration');
            $table->dropColumn('activities');
            $table->dropColumn('other_branches');
            $table->dropColumn('destination_category');
            $table->dropColumn('tour_category');
        });
    }
}
