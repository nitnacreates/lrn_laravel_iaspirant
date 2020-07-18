<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSubsciptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subsciptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->tinyInteger('plan_id');
            $table->tinyInteger('current_step')->default(1);
            $table->string('tr_identifier')->nullable();
            $table->dateTime('tr_completed')->nullable(); // update on verification
            $table->text('tr_meta')->nullable();
            $table->boolean('payment_verified')->default(0);
            $table->boolean('portal_processed')->default(0);

            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_subsciptions');
    }
}
