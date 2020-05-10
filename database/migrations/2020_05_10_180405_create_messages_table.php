<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('from_account_id')->unsigned();
            $table->enum('from_user_type',['s', 'n'])->default('s');
            $table->unsignedBigInteger('to_account_id')->unsigned();
            $table->tinyInteger('type');
            $table->string('message', 1000);
            $table->string('link',300)->nullable();
            $table->date('created');
            $table->boolean('seen')->default(0);

            $table->foreign('from_account_id')->references('id')->on('accounts');
            $table->foreign('to_account_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
