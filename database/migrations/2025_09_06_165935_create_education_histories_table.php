<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('education_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('profile_id');
            $table->string('institution', 255)->nullable();
            $table->string('degree', 191)->nullable();
            $table->string('field_of_study', 191)->nullable();
            $table->unsignedSmallInteger('start_year')->nullable();
            $table->unsignedSmallInteger('end_year')->nullable();
            $table->json('extra')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('profile_id')->references('id')->on('alumni_profiles')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('education_history');
    }
};
