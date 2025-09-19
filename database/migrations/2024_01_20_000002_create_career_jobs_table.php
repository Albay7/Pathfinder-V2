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
        Schema::create('career_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('industry'); // technology, business, finance, healthcare, etc.
            $table->string('level')->default('entry'); // entry, mid, senior, executive
            $table->string('employment_type')->default('full-time'); // full-time, part-time, contract, freelance
            $table->json('required_skills')->nullable(); // Array of required skills
            $table->json('preferred_skills')->nullable(); // Array of preferred skills
            $table->json('responsibilities')->nullable(); // Array of job responsibilities
            $table->json('mbti_compatibility')->nullable(); // MBTI types and compatibility scores
            $table->text('mbti_explanation')->nullable(); // Why this job fits certain MBTI types
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->string('salary_currency', 3)->default('PHP');
            $table->json('education_requirements')->nullable(); // Required education level/courses
            $table->integer('experience_years_min')->default(0);
            $table->integer('experience_years_max')->nullable();
            $table->json('growth_opportunities')->nullable(); // Career advancement paths
            $table->text('work_environment')->nullable(); // Description of work environment
            $table->boolean('remote_available')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['industry', 'level']);
            $table->index(['employment_type', 'remote_available']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_jobs');
    }
};