<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEssayReactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('essay_reactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('essay_response_id')->unsigned();
            $table->unsignedBigInteger('account_id')->unsigned();
            $table->tinyInteger('type')->nullable();
            $table->tinyInteger('score')->nullable();
            $table->string('comment', 300)->nullable();

            $table->unique(['essay_response_id', 'account_id'], 'essay_reaction_unique');
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('essay_response_id')->references('id')->on('essay_responses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('essay_reactions');
    }
}
