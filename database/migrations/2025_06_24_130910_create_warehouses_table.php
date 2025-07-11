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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->bigIncrements('warehouse_id'); 
            $table->string('name');
            $table->string('location');
            $table->integer('capacity')->nullable();
            $table->string('manager_name')->nullable();
            $table->string('contact_info')->nullable(); // fixed typo

            // Foreign key linking to users table (optional manager user)
            $table->foreignId('manager_id')->nullable()->constrained('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
