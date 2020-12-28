<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStudentFielsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('fatherName')->nullable();
            $table->string('fatherPhone')->nullable();
            $table->string('fatherJob')->nullable();
            $table->string('introduce')->nullable();
            $table->timestamp('birthday')->nullable();
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
            $table->dropColumn(['fatherName']);
            $table->dropColumn(['fatherPhone']);
            $table->dropColumn(['fatherJob']);
            $table->dropColumn(['introduce']);
            $table->dropColumn(['birthday']);
        });
    }
}
