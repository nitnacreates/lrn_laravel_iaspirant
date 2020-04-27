<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrelimRostersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prelim_rosters', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('title');
            $table->string('syllabus', 2000);
            $table->string('resource', 2000);
            $table->tinyInteger('weightage');
            $table->boolean('locked')->default(1);
            $table->date('available_from');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prelim_rosters');
    }
}
