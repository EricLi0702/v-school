<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToLiveLectures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('live_lectures', function (Blueprint $table) {
            $table->enum('status', ['before', 'running', 'finish'])->default('before');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('live_lectures', function (Blueprint $table) {
            $table->dropColumn(['status']);
        });
    }
}
