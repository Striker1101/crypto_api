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
        Schema::create('traders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string("position")->default("Senior broker trading personnel");
            $table->string('profile_picture')->nullable(); // Profile image URL
            $table->string('rating')->default("Pro Trader");
            $table->decimal('ROI', 10, 2)->default(0); // Return on Investment %
            $table->decimal('PnL', 15, 2)->default(0); // Profit and Loss
            $table->decimal('investment', 20, 2)->default(0); // Total investment
            $table->integer('ranking')->default(0); // Ranking among traders
            $table->boolean(column: 'display')->default(false); // Ranking among traders
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traders');
    }
};
