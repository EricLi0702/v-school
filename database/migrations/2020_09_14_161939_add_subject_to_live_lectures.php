<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubjectToLiveLectures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('live_lectures', function (Blueprint $table) {
            $table->string('grade')->nullable();
            $table->string('subject')->nullable();
            $table->dropColumn('cover_image');
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
            $table->dropColumn(['grade']);
            $table->dropColumn(['subject']);
            $table->string('cover_image');
        });
    }
}
