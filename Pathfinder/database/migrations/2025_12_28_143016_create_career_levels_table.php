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
        Schema::create('career_levels', function (Blueprint $table) {
            $table->id();
            $table->string('role_name'); // Job title (e.g., "Cybersecurity Analyst")
            $table->string('level'); // Career level (e.g., "Mid-Level", "Senior")
            $table->text('description'); // Detailed role description from job sites
            $table->decimal('salary_min', 10, 2)->nullable(); // Minimum salary
            $table->decimal('salary_max', 10, 2)->nullable(); // Maximum salary
            $table->string('salary_currency', 3)->default('PHP'); // Currency code
            $table->json('responsibilities')->nullable(); // Array of key responsibilities
            $table->json('required_skills')->nullable(); // Array of required technical/soft skills
            $table->json('preferred_qualifications')->nullable(); // Nice-to-have qualifications
            $table->string('data_version'); // Format: YYYY-MM (e.g., "2025-12")
            $table->boolean('is_current')->default(true); // Flag for latest data version
            $table->timestamp('scraped_at')->nullable(); // When data was collected
            $table->string('data_source')->nullable(); // Source of data (JobStreet, Kalibrr, etc.)
            $table->timestamps();

            // Indexes for fast queries
            $table->index('role_name');
            $table->index('is_current');
            $table->index(['role_name', 'level', 'is_current']);
            $table->index('data_version');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_levels');
    }
};
