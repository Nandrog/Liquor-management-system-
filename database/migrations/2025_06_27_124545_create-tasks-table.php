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

            $table->unsignedBigInteger('employee_id')->nullable();

            // Correct foreign key constraint
            $table->foreign('employee_id')
                  ->references('id') // assumes employees table uses id
                  ->on('employees')
                  ->onDelete('cascade');

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
