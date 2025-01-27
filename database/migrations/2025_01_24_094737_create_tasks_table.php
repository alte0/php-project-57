<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name');
            $table->string('description')->nullable();

            $table->integer('status_id'); // id статуса
            $table->foreign('status_id')->references('id')->on('task_statuses');

            $table->integer('created_by_id'); // Создатель задачи
            $table->foreign('created_by_id')->references('id')->on('users');

            $table->integer('assigned_to_id')->nullable(); // Тот на кого поставлена задача
            $table->foreign('assigned_to_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
