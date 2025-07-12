<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('role');
            $table->string('email')->unique();
            $table->string('skillset');

            // Foreign key referencing warehouses.warehouse_id
            $table->unsignedBigInteger('warehouse_id')->nullable();

            $table->foreign('warehouse_id')
                  ->references('warehouse_id')
                  ->on('warehouses')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
