<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fences', function (Blueprint $table) {
            $table->id();
            $table->string('imei');
            $table->string('fenceName')->unique();
            $table->string('fenceType');
            $table->string('inOutType');
            $table->longText('location');
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
        Schema::dropIfExists('fences');
    }
}
