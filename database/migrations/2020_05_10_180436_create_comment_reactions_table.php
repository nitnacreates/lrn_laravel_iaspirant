<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentReactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_reactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('comment_id')->unsigned();
            $table->unsignedBigInteger('account_id')->unsigned();
            $table->enum('reaction',[1, 2]);

            $table->unique(['comment_id', 'account_id'], 'comment_account');
            $table->foreign('comment_id')->references('id')->on('comments');
            $table->foreign('account_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_reactions');
    }
}
