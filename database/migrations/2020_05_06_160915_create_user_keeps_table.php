<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserKeepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_keeps', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->enum('gender',['f', 'm'])->default('m');
            $table->string('first_name', 80)->nullable();
            $table->string('last_name', 80)->nullable();
            $table->date('dob')->nullable();
            $table->tinyInteger('category')->nullable();
            $table->tinyInteger('qualification_type')->nullable();
            $table->string('qualification', 100)->nullable();   //highest qualification
            $table->string('upsc_roll', 20)->nullable();
            $table->tinyInteger('total_attempts')->nullable();
            $table->tinyInteger('max_stage')->nullable(); // max stage reached
            $table->string('phone', 16)->nullable();
            $table->boolean('phone_validated')->default(0);
            $table->string('phone_otp', 10)->nullable();
            $table->string('phone_data', 1000)->nullable();
            $table->string('transaction_data', 1000)->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->primary('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_keeps');
    }
}
