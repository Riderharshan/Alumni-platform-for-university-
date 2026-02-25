<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::create('professor_courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('professor_id');
            $table->unsignedSmallInteger('course_id');
            $table->timestamp('created_at')->nullable();

            $table->foreign('professor_id')->references('id')->on('professors')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');

            $table->unique(['professor_id','course_id'], 'uniq_prof_course');
        });
    }

    public function down()
    {
        Schema::dropIfExists('professor_courses');
    }
}
;