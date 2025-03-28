<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->decimal('balance', 15, 2)->default(0);
            $table->decimal('earning', 15, 2)->default(0);
            $table->decimal('bonus', 10, 2)->default(10);

            $table->boolean('trade')->default(false);

            $table->unsignedBigInteger('account_type_id')->default(1);
            $table->foreign('account_type_id')->references('id')->on('account_types')->onDelete('cascade');

            $table->enum('account_stage', ['beginner', 'bronze', 'silver', 'gold', 'premium'])->default('beginner');

            $table->timestamp('trade_changed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
