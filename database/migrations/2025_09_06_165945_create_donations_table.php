<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('amount', 14, 2);
            $table->string('payment_provider', 50)->nullable();
            $table->string('provider_payment_id', 191)->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->enum('status', ['pending','completed','failed','refunded'])->default('pending');
            $table->timestamp('donated_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('campaign_id')->references('id')->on('donation_campaigns')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('donations');
    }
};
