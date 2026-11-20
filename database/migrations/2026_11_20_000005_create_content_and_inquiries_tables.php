<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category', 64)->default('Advisory');
            $table->text('summary')->nullable();
            $table->longText('body');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_published')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('department');
            $table->string('employment_type', 64)->default('Permanent');
            $table->string('salary_grade', 64)->nullable();
            $table->unsignedInteger('vacancies_count')->default(1);
            $table->date('deadline')->nullable();
            $table->longText('description');
            $table->longText('qualifications')->nullable();
            $table->boolean('is_open')->default(true);
            $table->timestamps();
        });

        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_info');
            $table->string('subject');
            $table->text('message');
            $table->boolean('privacy_consented')->default(false);
            $table->string('status', 32)->default('new');
            $table->text('staff_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
        Schema::dropIfExists('job_postings');
        Schema::dropIfExists('announcements');
    }
};
