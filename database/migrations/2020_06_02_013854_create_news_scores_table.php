<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_scores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_id')->unsigned();
            $table->date('news_show_on');
            $table->float('score', 3, 2)->nullable();

            $table->unique(['news_show_on', 'account_id'], 'news_account_unique');

            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('news_show_on')->references('show_on')->on('news');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_scores');
    }
}
