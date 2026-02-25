<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::create('experience_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('profile_id');
            $table->string('company', 255)->nullable();
            $table->string('title', 191)->nullable();
            $table->string('location', 191)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('description')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('profile_id')->references('id')->on('alumni_profiles')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('experience_history');
    }
};
