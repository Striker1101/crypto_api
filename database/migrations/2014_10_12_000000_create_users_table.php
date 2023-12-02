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
            $table->enum('type', ['user', 'admin'])->default('user');


            // phone number
            $table->string('phone_number')->nullable();

            // location
            $table->string('street');
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');

            // Add image_url and image_id fields
            $table->string('image_url')->nullable();
            $table->unsignedInteger('image_id')->nullable();

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
