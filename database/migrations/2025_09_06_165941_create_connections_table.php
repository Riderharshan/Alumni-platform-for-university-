<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::create('connections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('requester_id');
            $table->unsignedBigInteger('requestee_id');
            $table->enum('status', ['pending','accepted','rejected','blocked'])->default('pending');
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('responded_at')->nullable();

            $table->foreign('requester_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('requestee_id')->references('id')->on('users')->onDelete('cascade');

            // unique undirected pair: using DB-level expression not available, so enforce in app or use ordered pair unique
            // We'll add a unique constraint on ordered pair to avoid duplicates of same direction, and application will ensure ordering
            $table->unique(['requester_id','requestee_id'], 'uniq_connection_pair');
        });
    }

    public function down()
    {
        Schema::dropIfExists('connections');
    }
};
