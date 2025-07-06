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
            $table->id();$table->string('name');
            $table->string('sku')->unique();
            $table->string('type')->nullable();
            $table->text('description')->nullable();
            $table->integer('reorder_level')->default(0);
            $table->decimal('unit_price', 10, 2);
            $table->string('unit_of_measure');
            $table->integer('stock')->default(0);
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->nullable()->constrained()->onDelete('cascade');
             // This tracks the primary supplier/vendor, if any.
            $table->foreignId('user_id')->nullable()->comment('Primary Supplier/Vendor ID')->constrained('users');
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
