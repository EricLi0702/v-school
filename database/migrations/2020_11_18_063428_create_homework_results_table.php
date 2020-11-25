<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeworkResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homework_results', function (Blueprint $table) {
            $table->id();
            $table->longText('answerData');
            $table->unsignedBigInteger('homeworkId');
            $table->unsignedBigInteger('userId');
            $table->foreign('homeworkId')->references('id')->on('bulletin_boards')->onDelete('cascade');
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('homework_results');
    }
}
