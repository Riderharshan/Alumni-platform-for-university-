<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('professors', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('phone');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::table('professors', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};