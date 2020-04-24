<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheorySummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theory_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('theory_id')->unsigned();
            $table->unsignedBigInteger('account_id')->unsigned();
            $table->enum('user_type',['s', 'n'])->default('n');
            $table->boolean('locked')->default(1);
            $table->text('theory_summary');

            $table->timestamps();
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->unique(['theory_id', 'account_id'], 'theory_summary_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theory_summaries');
    }
}
