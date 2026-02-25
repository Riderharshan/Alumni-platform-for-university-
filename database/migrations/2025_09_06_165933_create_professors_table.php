<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessorsTable extends Migration
{
    public function up()
    {
        Schema::create('professors', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('staff_number', 64)->nullable()->unique();
            $table->string('full_name', 191);
            $table->string('display_name', 191)->nullable();
            $table->string('title', 100)->nullable();
            $table->string('photo_url', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone', 30)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedSmallInteger('department_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->index('full_name', 'idx_professors_fullname');
            $table->index(['department_id'], 'idx_professors_dept');
        });
    }

    public function down()
    {
        Schema::dropIfExists('professors');
    }
}
