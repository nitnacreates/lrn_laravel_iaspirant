<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTheoryContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theory_contents', function (Blueprint $table) {
            $table->unsignedBigInteger('theory_id')->unsigned();
            $table->text('theory_content');
            $table->text('theory_reference')->nullable();

            $table->unique('theory_id');
            $table->foreign('theory_id')->references('id')->on('theories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theory_contents');
    }
}
