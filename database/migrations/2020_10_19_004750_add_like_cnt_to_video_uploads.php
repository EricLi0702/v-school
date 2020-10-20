<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLikeCntToVideoUploads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_uploads', function (Blueprint $table) {
            $table->json('view_cnt')->nullable();
            $table->json('like_cnt')->nullable();
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
