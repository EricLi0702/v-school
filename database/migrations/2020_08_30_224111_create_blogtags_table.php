<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogtagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogtags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tagId');
            $table->unsignedBigInteger('blogId');
            $table->timestamps();
            $table->foreign('tagId')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('blogId')->references('id')->on('blogs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogtags');
    }
}
