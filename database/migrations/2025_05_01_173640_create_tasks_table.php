<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // corresponds to int(11) NOT NULL
            $table->unsignedInteger('user_id')->nullable();
            $table->string('title', 255);
            $table->text('content');
            $table->tinyInteger('status');
            $table->integer('percent');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
}
