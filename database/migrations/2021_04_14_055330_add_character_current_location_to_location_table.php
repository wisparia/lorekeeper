<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCharacterCurrentLocationToLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->boolean('is_character_current')->default(0);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
}
