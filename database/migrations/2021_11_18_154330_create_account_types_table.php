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
        Schema::create('account_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('spreads', 10, 2)->nullable();
            $table->string('leverage')->nullable();
            $table->boolean('scalping')->default(false);
            $table->boolean('negative_balance_protection')->default(false);
            $table->string('stop_out')->nullable();
            $table->boolean('swap_free')->default(false);
            $table->decimal('minimum_trade_volume', 10, 2)->nullable();
            $table->boolean('hedging_allowed')->default(false);
            $table->boolean('daily_signals')->default(false);
            $table->boolean('video_tutorials')->default(false);
            $table->boolean('dedicated_account_manager')->default(false);
            $table->boolean('daily_market_review')->default(false);
            $table->boolean('financial_plan')->default(false);
            $table->boolean('risk_management_plan')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_types');
    }
};
