<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFixedTopToBulletinBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bulletin_boards', function (Blueprint $table) {
            $table->boolean('fixed_top')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bulletin_boards', function (Blueprint $table) {
            $table->dropColumn(['fixed_top']);
        });
    }
}
