<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('reporter_id');
            $table->string('reportable_type', 100);
            $table->unsignedBigInteger('reportable_id');
            $table->string('reason', 100);
            $table->text('details')->nullable();
            $table->enum('status', ['pending','in_review','dismissed','actioned'])->default('pending');
            $table->unsignedBigInteger('assigned_admin_id')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('action_taken', 191)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->foreign('reporter_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_admin_id')->references('id')->on('users')->onDelete('set null');

            $table->index(['reportable_type','reportable_id'], 'idx_reportable');
            $table->index('status', 'idx_reports_status');
            $table->index('reporter_id', 'idx_reports_reporter');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};
