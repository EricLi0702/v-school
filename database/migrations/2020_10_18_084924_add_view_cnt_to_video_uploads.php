<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddViewCntToVideoUploads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_uploads', function (Blueprint $table) {
            $table->unsignedBigInteger('view_cnt')->default('0');
            $table->unsignedBigInteger('like_cnt')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('video_uploads', function (Blueprint $table) {
            $table->dropColumn(['view_cnt']);
            $table->dropColumn(['like_cnt']);
        });
    }
}
