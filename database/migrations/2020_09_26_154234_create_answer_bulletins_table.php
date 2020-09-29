<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswerBulletinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_bulletins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bulletinId');
            $table->unsignedBigInteger('userId');
            $table->text('answerData');
            $table->timestamps();
            $table->foreign('bulletinId')->references('id')->on('bulletin_boards')->onDelete('cascade');
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answer_bulletins');
    }
}
