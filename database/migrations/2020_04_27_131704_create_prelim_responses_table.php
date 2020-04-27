<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrelimResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prelim_responses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('prelim_roster_id')->unsigned();
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->tinyInteger('attempt')->nullable(false);
            $table->string('final_response', 5000)->comment('serialize display answer');
            $table->string('live_response', 5000)->comment('serialize display answer');
            $table->tinyInteger('correct_ans');
            $table->tinyInteger('wrong_ans');
            $table->float('marks', 3, 2);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('report')->nullable();

            $table->unique(['prelim_roster_id', 'user_id', 'attempt'], 'attempt_unique');
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('prelim_roster_id')->references('id')->on('prelim_rosters');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prelim_responses');
    }
}
