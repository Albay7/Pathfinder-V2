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
        Schema::create('career_ladders', function (Blueprint $table) {
            $table->id();
            $table->string('target_role'); // The ultimate career goal (e.g., "Cybersecurity Architect")
            $table->string('step_role'); // Specific role in the progression (e.g., "Security Analyst")
            $table->string('level'); // Career level (e.g., "Entry-Level", "Mid-Level", "Senior")
            $table->integer('sequence_order'); // Order in the career progression (1, 2, 3...)
            $table->json('prerequisites')->nullable(); // Required skills, certifications, experience
            $table->integer('typical_duration_months')->nullable(); // Average time spent at this level
            $table->integer('min_years_experience')->default(0); // Minimum years of experience
            $table->integer('max_years_experience')->nullable(); // Maximum years of experience
            $table->text('transition_requirements')->nullable(); // What's needed to move to next level
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes for performance
            $table->index('target_role');
            $table->index(['target_role', 'sequence_order']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_ladders');
    }
};
