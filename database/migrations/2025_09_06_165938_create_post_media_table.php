<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::create('post_media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('post_id');
            $table->string('url', 255);
            $table->string('type', 50)->default('image');
            $table->json('meta')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_media');
    }
};
