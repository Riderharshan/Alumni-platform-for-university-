<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('follower_id');
            $table->unsignedBigInteger('followee_id');
            $table->timestamp('created_at')->nullable();

            $table->foreign('follower_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('followee_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['follower_id','followee_id'], 'uniq_follow');
        });
    }

    public function down()
    {
        Schema::dropIfExists('follows');
    }
};
