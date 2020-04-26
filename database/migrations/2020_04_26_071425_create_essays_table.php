<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEssaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('essays', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('framework');
            $table->unsignedSmallInteger('word_limit')->comment('multiples of 50');
            $table->string('sub',50);
            $table->unsignedInteger('topic_id');
            $table->tinyInteger('weightage');
            $table->boolean('locked')->default(1);
            $table->date('created_on');

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
        Schema::dropIfExists('essays');
    }
}
