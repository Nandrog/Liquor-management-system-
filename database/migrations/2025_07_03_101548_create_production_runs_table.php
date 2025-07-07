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
        Schema::create('production_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // The manufacturer who ran it
            $table->foreignId('factory_id')->constrained();
            $table->foreignId('product_id')->constrained(); // The finished good produced
            $table->integer('quantity_produced'); // Number of bottles
            $table->decimal('cost_of_materials', 12, 2);
            $table->timestamp('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_runs');
    }
};
