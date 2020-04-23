<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sub',50);
            $table->string('title');
            $table->mediumInteger('parent')->default(0)->unsigned();
            $table->smallInteger('level')->default(0)->unsigned();
            $table->longText('about')->nullable();
            $table->enum('category',['p', 'm', 'b'])->default('b');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topics');
    }
}
