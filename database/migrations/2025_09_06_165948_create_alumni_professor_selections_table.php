<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void
    {
        // Drop existing table first (if present) to ensure fresh schema
//Schema::dropIfExists('alumni_professor_selections');

        // Create new table using user_id referencing users table
        Schema::create('alumni_professor_selections', function (Blueprint $table) {
            $table->bigIncrements('id');

            // store the alumni as a user_id (unsigned big integer to match users.id)
            $table->unsignedBigInteger('user_id')->nullable()->index();

            // professor row (as before)
            $table->unsignedBigInteger('professor_id');

            $table->unsignedSmallInteger('batch_year')->nullable();
            $table->string('branch', 100)->nullable();
            $table->unsignedSmallInteger('joined_year')->nullable();
            $table->unsignedSmallInteger('passed_out_year')->nullable();
            $table->string('context', 100)->default('profile_selection');
            $table->timestamp('selected_at')->nullable();
            $table->json('extra')->nullable();
            $table->timestamps(); // optional; you can remove if you prefer only selected_at

            // foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('professor_id')->references('id')->on('professors')->onDelete('cascade');

            // unique & index
            $table->unique(['user_id','professor_id'], 'uniq_user_prof');
            $table->index(['professor_id','batch_year'], 'idx_sel_prof_batch');
        });
    }

    public function down()
    {
        Schema::dropIfExists('alumni_professor_selections');
    }
};
