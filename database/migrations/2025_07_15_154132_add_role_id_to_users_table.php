<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
    if (!Schema::hasColumn('users', 'role_id')) {
        $table->unsignedBigInteger('role_id')->nullable()->after('email');
        $table->foreign('role_id')
              ->references('id')
              ->on('roles')
              ->onDelete('set null');
    }
});

    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};

