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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('role');
            $table->string('email')->unique();
            $table->string('skillset');
            $table->unsignedBigInteger('warehouse_id')->nullable();

            // Properly defined foreign key constraint
            $table->foreign('warehouse_id')
                  ->references('warehouse_id')
                  ->on('warehouses')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
