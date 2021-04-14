<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrentLocationToCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */ 
    public function up()
    {

        Schema::table('characters', function (Blueprint $table) {
            $table->integer('current_location_id')->unsigned()->nullable()->default(null);
            $table->foreign('current_location_id')->references('id')->on('locations')->onDelete('set null');
            $table->dateTime('current_location_changed')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->dropForeign(['current_location_id']);
            $table->dropColumn('current_location_id');
            $table->dropColumn('current_location_changed');
        });
    }
}