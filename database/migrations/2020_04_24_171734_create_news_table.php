<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('news_title',300);
            $table->string('news_link',300)->unique();
            $table->string('meta_key',300)->nullable();
            $table->string('meta_descr',500)->nullable();
            $table->text('news_content');
            $table->text('self_note', 2000)->nullable();
            $table->string('sub',70);
            $table->unsignedInteger('topic_id')->unsigned();
            $table->enum('category',['p', 'm', 'b'])->default('b');
            $table->enum('type',['dn', 'ed'])->default('dn');
            $table->tinyInteger('weightage');

            $table->timestamps();

            $table->foreign('topic_id')->references('id')->on('topics');
        });

        DB::statement('ALTER TABLE news ADD FULLTEXT full_news_content (news_content)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
