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
        Schema::table('vendor_applications', function (Blueprint $table) {

            if (!Schema::hasColumn('vendor_applications', 'visit_scheduled_for')) {
                $table->timestamp('visit_scheduled_for')->nullable()->after('pdf_path');
            }

            if (!Schema::hasColumn('vendor_applications', 'validation_notes')) {
                $table->text('validation_notes')->nullable()->after('visit_scheduled_for');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('vendor_applications', function (Blueprint $table) {
            $table->dropColumn(['visit_scheduled_for', 'validation_notes']);
        });
    }
};
