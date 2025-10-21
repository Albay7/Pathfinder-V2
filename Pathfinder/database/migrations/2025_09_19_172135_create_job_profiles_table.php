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
        Schema::create('job_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->string('company')->nullable();
            $table->text('description');
            $table->string('source'); // remoteok, stackoverflow, etc.
            $table->string('url')->nullable();
            
            // Skill vectors for cosine similarity
            $table->decimal('programming', 3, 2)->default(0);
            $table->decimal('web_development', 3, 2)->default(0);
            $table->decimal('database', 3, 2)->default(0);
            $table->decimal('cloud_devops', 3, 2)->default(0);
            $table->decimal('mobile_development', 3, 2)->default(0);
            $table->decimal('data_science', 3, 2)->default(0);
            $table->decimal('ui_ux', 3, 2)->default(0);
            $table->decimal('project_management', 3, 2)->default(0);
            $table->decimal('communication', 3, 2)->default(0);
            $table->decimal('leadership', 3, 2)->default(0);
            $table->decimal('analytical_thinking', 3, 2)->default(0);
            $table->decimal('problem_solving', 3, 2)->default(0);
            
            // Raw skill data
            $table->json('technical_skills')->nullable();
            $table->json('soft_skills')->nullable();
            $table->json('frameworks_libraries')->nullable();
            $table->json('tools')->nullable();
            $table->json('skill_scores')->nullable();
            
            // Metadata
            $table->timestamp('scraped_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index(['job_title', 'is_active']);
            $table->index('source');
            $table->index('scraped_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_profiles');
    }
};
