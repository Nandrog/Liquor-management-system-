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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
            $table->string('type')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->nullable()->constrained()->onDelete('cascade');
            $table->string('status');
            $table->timestamp('paid_at')->nullable()->after('status');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('payment_status')->default('pending'); // e.g., pending, paid, failed
            $table->string('transaction_id')->nullable(); // To store the Stripe Payment Intent ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
