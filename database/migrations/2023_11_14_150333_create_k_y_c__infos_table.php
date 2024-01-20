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
        Schema::create('kyc_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('ssn')->unique();
            $table->string('number')->nullable(); // Add the 'number' field
            $table->string('DLF_image_url')->nullable(); // Add the 'DLF_imageUrl' field
            $table->string('DLF_image_id')->nullable(); // Add the 'DLF_imageID' field
            $table->string('DLB_image_url')->nullable(); // Add the 'DLB_imageUrl' field
            $table->string('DLB_image_id')->nullable();
            $table->boolean('verified')->default(false); //

            // Ensure that SSN is encrypted and stored securely
            // Add other KYC-related fields as needed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('k_y_c__infos');
    }
};
