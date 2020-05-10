<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('page_link',300);
            $table->unsignedBigInteger('account_id')->unsigned();
            $table->string('comment', 500);
            $table->boolean('moderated')->default(1);
            $table->boolean('soft_delete')->default(0);
            $table->unsignedBigInteger('thread_id')->default(0);

            $table->timestamps();
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
        Schema::dropIfExists('comments');
    }
}
