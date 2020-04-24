<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('theory_title');
            $table->text('theory_content');
            $table->text('theory_reference')->nullable();
            $table->unsignedBigInteger('account_id')->unsigned();
            $table->enum('user_type',['s', 'n'])->default('n');
            $table->string('sub',70);
            $table->unsignedInteger('topic_id')->unsigned();
            $table->enum('category',['p', 'm', 'b'])->default('b'); // redundant but for searching
            $table->tinyInteger('weightage');
            $table->boolean('locked')->default(1);
            
            $table->timestamps();

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
        Schema::dropIfExists('theories');
    }
}
