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
        Schema::table('traders', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_referral_id')->nullable()->after('id');
            $table->foreign('owner_referral_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('traders', function (Blueprint $table) {
            $table->dropForeign(['owner_referral_id']);
            $table->dropColumn('owner_referral_id');
        });
    }
};
