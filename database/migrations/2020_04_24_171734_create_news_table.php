<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('news_title',300);
            $table->text('news_content');
            $table->string('sub',70);
            $table->unsignedInteger('topic_id')->unsigned();
            $table->enum('category',['p', 'm', 'b'])->default('b');
            $table->enum('type',['dn', 'ed'])->default('dn');
            $table->tinyInteger('weightage');
            $table->date('visible_from');
            $table->boolean('locked')->default(1);

            $table->timestamps();

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
        Schema::dropIfExists('news');
    }
}
