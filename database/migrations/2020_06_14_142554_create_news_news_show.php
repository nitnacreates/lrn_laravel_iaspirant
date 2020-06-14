<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsNewsShow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_news_show', function (Blueprint $table) {
            $table->unsignedBigInteger('news_id')->unsigned();
            $table->unsignedBigInteger('news_show_id')->unsigned();

            $table->unique(['news_id', 'news_show_id'], 'news_news_unique');
            $table->foreign('news_id')->references('id')->on('news');
            $table->foreign('news_show_id')->references('id')->on('news_shows');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_news_shows');
    }
}
