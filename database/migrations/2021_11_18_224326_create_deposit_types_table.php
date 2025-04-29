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
        Schema::create('deposit_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('symbol')->nullable();
            $table->string('currency')->nullable();
            $table->enum('type', ['bank_transfer', 'crypto', 'mobile', 'card', 'paypal', 'others'])->default("crypto");
            $table->decimal('min_limit', 15, 2)->nullable();
            $table->decimal('max_limit', 15, 2)->nullable();
            $table->unsignedBigInteger("owner_referral_id")->nullable(); // refer owner user
            $table->foreign('owner_referral_id')->references('id')->on('users')->onDelete('cascade');
            $table->json('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_types');
    }
};
