<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('public_info_officer')->after('password');
            $table->string('department')->nullable()->after('role');
            $table->json('assigned_categories')->nullable()->after('department');
            $table->boolean('is_active')->default(true)->after('assigned_categories');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'department', 'assigned_categories', 'is_active']);
        });
    }
};
