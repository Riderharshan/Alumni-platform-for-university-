<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::create('professor_endorsements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('professor_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedTinyInteger('rating')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('professor_id')->references('id')->on('professors')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['professor_id','user_id'], 'uniq_prof_user');
        });
    }

    public function down()
    {
        Schema::dropIfExists('professor_endorsements');
    }
}
;