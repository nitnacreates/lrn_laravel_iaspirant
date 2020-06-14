<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEssayResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('essay_responses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('essay_id')->unsigned();
            $table->unsignedBigInteger('account_id')->unsigned();
            $table->text('response_content')->nullable();
            $table->boolean('highlighted')->default(0);
            $table->boolean('locked')->default(0);
            $table->timestamp('completed_on', 0)->nullable();
            $table->timestamps();

            $table->unique(['essay_id', 'account_id'], 'essay_responses_unique');
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('essay_id')->references('id')->on('essays');
        });

        DB::statement('ALTER TABLE essay_responses ADD FULLTEXT full_essay_response_content (response_content)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('essay_responses');
    }
}
