<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique()->nullable();
            
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->onDelete('cascade');

            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');

            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null'); // ✅ added

            $table->string('type')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('cascade');

            $table->string('shipping_address')->nullable();
            $table->string('city')->nullable();
            $table->string('phone_number')->nullable();

            $table->string('status');
            $table->timestamp('delivered_at')->nullable();
           
            $table->timestamp('paid_at')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('payment_status')->default('pending');
            $table->string('transaction_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
}
