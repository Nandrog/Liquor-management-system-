<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shift_schedules', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employee_id')->nullable();

            $table->foreign('employee_id')
                  ->references('id') // Correct column name on employees table
                  ->on('employees')
                  ->onDelete('cascade');

            $table->timestamp('start_time')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('end_time')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->float('break_hours')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_schedules');
    }
};
