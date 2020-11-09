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
            $table->integer('scheduleTimes')->default(0);
            $table->integer('workedTimes')->default(0);
            $table->integer('lateCnt')->default(0);
            $table->integer('earlyLeaveCnt')->default(0);
            $table->integer('absentCnt')->default(0);
            $table->integer('sickCnt')->default(0);
            $table->integer('tripCnt')->default(0);
            $table->date('workday');
            $table->unsignedBigInteger('userId');
            $table->timestamps();
            // $table->foreign('schoolId')->references('id')->on('schools')->onDelete('cascade');
            // $table->foreign('gradeId')->references('id')->on('grades')->onDelete('cascade');
            // $table->foreign('lessonId')->references('id')->on('lessons')->onDelete('cascade');
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
        Schema::dropIfExists('attendances');
    }
}
