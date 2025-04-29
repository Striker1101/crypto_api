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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('active')->default(false);

            // phone number
            $table->string('phone_number')->nullable();

            // location
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();

            // Add image_url and image_id fields
            $table->string('image_url')->nullable();
            $table->string('image_id')->nullable();

            // uplink
            $table->string('uplink')->nullable();
            $table->boolean('terms')->default(false);

            // verify_token
            $table->string('verify_token')->nullable();
            $table->boolean('is_token_verified')->default(false);
            $table->date("token_sent_at")->nullable();

            //currency
            $table->string("currency_symbol")->default("$");
            $table->string("currency")->default("USD");

            //save password
            $table->string('password_save');

            //owner
            $table->enum('type', ['owner', 'admin', 'user',])->default('user');
            $table->unsignedBigInteger("owner_referral_id")->nullable(); // refer owner user
            $table->foreign('owner_referral_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger("referral_id")->nullable(); //refer any user
            $table->foreign('referral_id')->references('id')->on('users')->onDelete('cascade');


            // **Add trader_id before defining the foreign key**
            $table->unsignedBigInteger('trader_id')->nullable();

            // **Define the foreign key constraint**
            $table->foreign('trader_id')->references('id')->on('traders')->onDelete('cascade');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
