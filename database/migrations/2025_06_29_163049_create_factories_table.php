<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');

            // Manually specify the FK column type to match warehouses.warehouse_id
            $table->unsignedBigInteger('warehouse_id')->unique();

            $table->foreign('warehouse_id')
                  ->references('warehouse_id')  // reference custom PK
                  ->on('warehouses')
                  ->cascadeOnDelete();          // optional, deletes factory if warehouse deleted

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factories');
    }
};
