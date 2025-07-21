<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Match the warehouses primary key column name and type
            $table->unsignedBigInteger('from_warehouse_id')->nullable();
            $table->unsignedBigInteger('to_warehouse_id')->nullable();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('set null');

            $table->integer('quantity');
            $table->timestamp('moved_at');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign keys reference 'warehouse_id' column
            $table->foreign('from_warehouse_id')
                  ->references('warehouse_id')
                  ->on('warehouses')
                  ->onDelete('restrict');

            $table->foreign('to_warehouse_id')
                  ->references('warehouse_id')
                  ->on('warehouses')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
