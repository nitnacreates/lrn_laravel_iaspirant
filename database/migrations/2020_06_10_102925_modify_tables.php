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
     * Run all other migrations first, delete its entry from migrations and rerun this
     * @return void
     */
    public function up()
    {
        Schema::table('user_keeps', function (Blueprint $table) {
            $table->dropColumn('transaction_data');
        });
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

    }
}
