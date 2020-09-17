<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('all_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('content');
            $table->string('img');
            $table->unsignedBigInteger('from');
            $table->integer('readCnt')->default(0);
            $table->integer('likeCnt')->default(0);
            $table->boolean('isLiked')->default('0');
            $table->timestamps();
            $table->foreign('from')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('all_posts');
    }
}
