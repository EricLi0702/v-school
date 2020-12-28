<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('schoolName')->unique();
            $table->unsignedBigInteger('code')->unique();
            $table->unsignedBigInteger('phoneNum')->unique();
            $table->unsignedBigInteger('zipCode')->unique();
            $table->string('head');
            $table->json('address');
            $table->longText('introduce');
            $table->string('imgUrl')->default('/');
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
        Schema::dropIfExists('schools');
    }
}
