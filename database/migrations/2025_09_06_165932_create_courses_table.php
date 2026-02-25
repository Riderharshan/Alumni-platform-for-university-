<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 150);
            $table->string('code', 40)->nullable();
            $table->unsignedSmallInteger('department_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
