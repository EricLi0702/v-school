<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManagerFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('schoolId')->nullable();
            $table->string('gender')->nullable();
            $table->string('nation')->nullable();
            $table->unsignedBigInteger('cardNum')->nullable()->unique();
            $table->json('familyAddress')->nullable();
            $table->json('residenceAddress')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['schoolId']);
            $table->dropColumn(['gender']);
            $table->dropColumn(['nation']);
            $table->dropColumn(['cardNum']);
            $table->dropColumn(['familyAddress']);
            $table->dropColumn(['residenceAddress']);
        });
    }
}
