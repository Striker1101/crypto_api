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
            $table->enum('withdrawal_type', ['crypto', 'bank_transfer']);
            $table->boolean('status')->default(false);
            $table->decimal('amount', 10, 2); 
            $table->string('name')->nullable();
            $table->string('currency');
            $table->string('destination'); // This can store a cryptocurrency address or bank account details
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('withdraws');
    }
}
