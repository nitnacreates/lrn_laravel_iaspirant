<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
** DELETE entry from migration table
*/
class ModifyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

    public function previous()
    {
        // 2020-06-10
        DB::statement('ALTER TABLE news ADD FULLTEXT full_news_content (news_content)');
        DB::statement('ALTER TABLE essay_responses ADD FULLTEXT full_essay_response_content (response_content)');
    }
}
