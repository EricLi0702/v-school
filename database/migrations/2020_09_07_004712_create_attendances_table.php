<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('schoolId');
            // $table->unsignedBigInteger('gradeId');
            // $table->unsignedBigInteger('lessonId');
            $table->integer('scheduleTimes');
            $table->integer('workedTimes');
            $table->integer('lateCnt')->nullable();
            $table->integer('earlyLeaveCnt')->nullable();
            $table->integer('absentCnt')->nullable();
            $table->integer('sickCnt')->nullable();
            $table->integer('tripCnt')->nullable();
            $table->date('workday');
            $table->unsignedBigInteger('userId');
            $table->timestamps();
            // $table->foreign('schoolId')->references('id')->on('schools')->onDelete('cascade');
            // $table->foreign('gradeId')->references('id')->on('grades')->onDelete('cascade');
            // $table->foreign('lessonId')->references('id')->on('lessons')->onDelete('cascade');
            $table->foreign('userId')->references('id')->on('user_roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
