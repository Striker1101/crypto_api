<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up()
        {
            Schema::create('assets', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('image_url')->nullable();
                $table->integer('image_id')->nullable();
                $table->string('type');
                $table->timestamps();
            });
        }

        public function down()
        {
            Schema::dropIfExists('assets');
        }
};
