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
        Schema::create('vendor_applications', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_name');
            $table->string('contact_email')->unique();
            $table->string('pdf_path'); // To store the path of the uploaded PDF
            $table->enum('status', ['pending', 'approved', 'rejected', 'failed', 'passed'])->default('pending');
            $table->timestamp('visit_scheduled_for')->nullable();
            $table->text('validation_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_applications');
    }
};
