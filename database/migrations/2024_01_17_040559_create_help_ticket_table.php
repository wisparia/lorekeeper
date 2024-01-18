<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelpTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('help_ticket', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('user_id')->unsigned()->index();
            $table->integer('staff_id')->unsigned()->nullable()->default(null)->index();

            $table->string('url');
            $table->text('comments')->nullable()->default(null);
            $table->text('staff_comments')->nullable()->default(null);
            $table->text('parsed_staff_comments')->nullable()->default(null);

            $table->boolean('is_urgent')->default(0);
            $table->string('error_type')->nullable();

            $table->string('data', 512)->nullable()->default(null);

            $table->enum('status', ['Pending', 'Assigned', 'Closed'])->default('Pending');
            
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
        Schema::dropIfExists('help_ticket');
    }
}