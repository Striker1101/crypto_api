<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawsTable extends Migration
{
    public function up()
    {
        Schema::create('withdraws', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('withdrawal_type_id');
            $table->foreign('withdrawal_type_id')->references('id')->on('withdraw_types')->onDelete('cascade');
            $table->enum('status', ['pending', 'completed', 'rejected', "processing", "upgrade"])->default('pending');
            $table->boolean('added')->default(false);
            $table->decimal('amount', 10, 2);
            $table->unsignedBigInteger("owner_referral_id")->nullable(); // refer owner user
            $table->foreign('owner_referral_id')->references('id')->on('users')->onDelete('cascade');
            $table->json('details')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('withdraws');
    }
}
