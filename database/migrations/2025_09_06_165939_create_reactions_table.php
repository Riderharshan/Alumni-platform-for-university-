<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('reactable_type', 100);
            $table->unsignedBigInteger('reactable_id');
            $table->string('reaction_type', 50);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id','reactable_type','reactable_id'], 'uniq_reaction_per_user');
            $table->index(['reactable_type','reactable_id'], 'idx_reactable');
            $table->index('reaction_type', 'idx_reaction_type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reactions');
    }
};
