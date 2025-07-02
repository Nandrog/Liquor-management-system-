<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_payment_columns_to_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_status')->default('pending'); // e.g., pending, paid, failed
            $table->string('transaction_id')->nullable(); // To store the Stripe Payment Intent ID
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_status');
            $table->dropColumn('transaction_id');
        });
    }
};
