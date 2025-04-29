<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositsTable extends Migration
{
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger("deposit_type_id");
            $table->foreign('deposit_type_id')->references('id')->on('deposit_types')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('currency')->nullable();
            $table->enum('status', ['pending', 'completed', 'rejected', "processing", "upgrade"])->default('pending');
            $table->boolean('added')->default(false);
            $table->string('image_url')->nullable(); // Add the 'DLF_imageUrl' field
            $table->json('details')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger("owner_referral_id")->nullable(); // refer owner user
            $table->foreign('owner_referral_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('deposits');
    }
}
