<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegisteredMembersToLiveLectures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('live_lectures', function (Blueprint $table) {
            $table->unsignedBigInteger('registered_members')->default(0);
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
            $table->dropColumn(['registered_members']);
        });
    }
}
