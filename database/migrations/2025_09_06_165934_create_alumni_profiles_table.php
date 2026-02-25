<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlumniProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('alumni_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('usn', 30)->nullable();
            $table->string('display_name', 191)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->unsignedSmallInteger('batch_year')->nullable();

            $table->unsignedSmallInteger('course_id')->nullable();
            $table->unsignedSmallInteger('department_id')->nullable();
            $table->unsignedBigInteger('selected_professor_id')->nullable();

            $table->string('headline', 191)->nullable();
            $table->text('about')->nullable();

            $table->string('location_city', 100)->nullable();
            $table->string('location_state', 100)->nullable();
            $table->string('location_country', 100)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->boolean('is_mentor')->default(false);
            $table->json('mentor_categories')->nullable();
            $table->string('profile_picture', 255)->nullable();
            $table->boolean('profile_completed')->default(false);
            $table->enum('visibility', ['public','alumni_only','private'])->default('alumni_only');
            $table->json('extra')->nullable();
            $table->json('privacy_settings')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('set null');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('selected_professor_id')->references('id')->on('professors')->onDelete('set null');

            $table->index('usn', 'idx_alumni_usn');
            $table->index('batch_year', 'idx_alumni_batch');
            $table->index('course_id', 'idx_alumni_course');
            $table->index('department_id', 'idx_alumni_dept');
            $table->index(['location_city','location_state','location_country'], 'idx_alumni_location');
        });
    }

    public function down()
    {
        Schema::dropIfExists('alumni_profiles');
    }
}
