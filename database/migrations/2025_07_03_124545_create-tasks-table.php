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

            // Employee foreign key
            $table->foreignId('employee_id')
                  ->nullable()
                  ->constrained('employees')
                  ->onDelete('cascade');

            // Stock movement foreign key
            $table->foreignId('stock_movement_id')
                  ->nullable()
                  ->constrained('stock_movements')
                  ->nullOnDelete();

            // Task details
            $table->string('type');
            $table->string('priority');
            $table->timestamp('deadline');
            $table->string('status')->default('pending');
            $table->timestamps();
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
