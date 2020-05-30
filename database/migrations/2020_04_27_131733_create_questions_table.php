<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('type');
            $table->string('sub',70);
            $table->unsignedInteger('topic_id')->unsigned();
            $table->unsignedBigInteger('account_id')->unsigned();
            $table->enum('user_type',['s', 'n'])->default('n');
            $table->string('question', 2000);
            $table->string('option_a', 1000);
            $table->string('option_b', 1000);
            $table->string('option_c', 1000);
            $table->string('option_d', 1000);
            $table->enum('answer',['a', 'b', 'c', 'd']);
            $table->string('explanation', 5000);
            $table->string('secret_text', 5000)->nullable();
            $table->boolean('locked')->default(1);
            $table->date('show_on');

            $table->foreign('account_id')->references('id')->on('accounts');
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
        Schema::dropIfExists('questions');
    }
}
