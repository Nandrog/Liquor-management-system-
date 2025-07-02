<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // This column will link a manufacturer User to their Factory.
            // It MUST be nullable, as not all users (suppliers, customers) belong to a factory.
            // onDelete('set null') means if a factory is deleted, the user's factory_id becomes NULL
            // instead of deleting the user, which is much safer.
            $table->foreignId('factory_id')
                  ->nullable()
                  ->after('employee_id') // Places the column neatly in the table
                  ->constrained('factories')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // The 'down' method should always reverse the 'up' method.
            $table->dropForeign(['factory_id']);
            $table->dropColumn('factory_id');
        });
    }
};