<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('students_raw', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('usn', 30)->unique();
            $table->string('full_name', 191);
            $table->unsignedSmallInteger('batch_year')->nullable();
            $table->string('course', 100)->nullable();
            $table->string('department', 100)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('mobile', 30)->nullable()->index();
            $table->string('email', 255)->nullable();
            $table->string('gender', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->json('extra')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('students_raw');
    }
};
