<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_levels', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Define warehouse_id as unsignedBigInteger, referencing warehouses.warehouse_id
            

            $table->foreignId('warehouse_id')
                  ->references('warehouse_id')
                  ->on('warehouses')
                  ->constrained()
                  ->onDelete('cascade');

            $table->integer('quantity'); // The current on-hand quantity

            $table->unique(['product_id', 'warehouse_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_levels');
    }
};
