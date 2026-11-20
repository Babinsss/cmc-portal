<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricelist_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('last_updated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('pricelist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('pricelist_categories')->cascadeOnDelete();
            $table->string('item_code', 64)->index();
            $table->string('name')->index();
            $table->string('unit', 120)->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->boolean('is_available')->default(true);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['category_id', 'item_code']);
        });

        Schema::create('pricelist_import_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('pricelist_categories')->nullOnDelete();
            $table->string('filename');
            $table->unsignedInteger('rows_created')->default(0);
            $table->unsignedInteger('rows_updated')->default(0);
            $table->unsignedInteger('rows_skipped')->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricelist_import_logs');
        Schema::dropIfExists('pricelist_items');
        Schema::dropIfExists('pricelist_categories');
    }
};
