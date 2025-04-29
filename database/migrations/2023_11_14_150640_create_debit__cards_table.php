<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('debit_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('card_number');
            $table->date('expiration_date');
            $table->string('cvv');
            $table->unsignedBigInteger("owner_referral_id")->nullable(); // refer owner user
            $table->foreign('owner_referral_id')->references('id')->on('users')->onDelete('cascade');
            //type of card
            $table->enum('type', ['verve', 'master', 'visa', 'black'])->default('verve');
            // Add other card-related fields as needed
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debit__cards');
    }
};
