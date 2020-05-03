<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrelimQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prelim_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('prelim_roster_id')->unsigned();
            $table->tinyInteger('display_id')->nullable(false);
            $table->string('sub',70);
            $table->unsignedInteger('topic_id')->unsigned();
            $table->string('question', 2000);
            $table->string('option_a', 1000);
            $table->string('option_b', 1000);
            $table->string('option_c', 1000);
            $table->string('option_d', 1000);
            $table->enum('answer',['a', 'b', 'c', 'd']);
            $table->string('explanation', 5000);
            $table->string('secret_text', 5000);
            $table->unsignedSmallInteger('ques_ref_id')->nullable();
            $table->tinyInteger('weightage');

            $table->unique(['prelim_roster_id', 'display_id'], 'pre_ques_unique');
            
            $table->foreign('prelim_roster_id')->references('id')->on('prelim_rosters');
            $table->foreign('topic_id')->references('id')->on('topics');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prelim_questions');
    }
}
