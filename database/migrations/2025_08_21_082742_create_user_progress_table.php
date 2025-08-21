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
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('feature_type'); // 'career_guidance', 'career_path', 'skill_gap'
            $table->string('assessment_type')->nullable(); // 'course', 'job' for career guidance
            $table->json('questionnaire_answers')->nullable(); // Store questionnaire responses
            $table->string('recommendation')->nullable(); // Store the recommendation result
            $table->string('current_role')->nullable(); // For career path
            $table->string('target_role')->nullable(); // For career path and skill gap
            $table->json('current_skills')->nullable(); // For skill gap analysis
            $table->json('analysis_result')->nullable(); // Store complete analysis results
            $table->decimal('match_percentage', 5, 2)->nullable(); // For skill gap match percentage
            $table->boolean('completed')->default(false); // Whether the assessment was completed
            $table->timestamps();
            
            $table->index(['user_id', 'feature_type']);
            $table->index(['user_id', 'completed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
