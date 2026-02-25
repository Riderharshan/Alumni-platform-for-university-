<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->enum('post_type', ['story','job','update'])->default('story');
            $table->string('title', 191)->nullable();
            $table->text('body');
            $table->json('metadata')->nullable();
            $table->boolean('is_published')->default(true);
            $table->boolean('is_approved')->default(false);
            $table->enum('visibility', ['public','alumni_only','connections'])->default('alumni_only');
            $table->unsignedInteger('reports_count')->default(0);
            $table->boolean('is_flagged')->default(false);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('is_flagged', 'idx_posts_is_flagged');
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
