<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        Schema::create('donation_campaigns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 191);
            $table->string('slug', 191)->unique();
            $table->decimal('target_amount', 14, 2)->default(0.00);
            $table->decimal('collected_amount', 14, 2)->default(0.00);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donation_campaigns');
    }
};
