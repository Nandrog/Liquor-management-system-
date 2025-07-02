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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
             $table->string('name');
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->string('unit_of_measure');
            $table->integer('stock')->default(0);
            $table->integer('reorder_level')->default(0);
            $table->enum('type', ['finished_good', 'raw_material'])->default('finished_good');
            $table->foreignId('supplier_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
