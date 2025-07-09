<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recipe_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_product_id')->constrained('products');
            $table->decimal('quantity', 10, 4);
            $table->timestamps();
            $table->unique(['recipe_id', 'material_product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipe_materials');
    }
};
