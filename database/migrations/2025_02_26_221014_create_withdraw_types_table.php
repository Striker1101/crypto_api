<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('withdraw_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('wallet_address')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('routing_number')->nullable();
            $table->string('code')->nullable();
            $table->enum('type', ['bank', 'crypto']);
            $table->decimal('min_limit', 10, 2)->default(0);
            $table->decimal('max_limit', 10, 2)->default(10000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdraw_types');
    }
};
