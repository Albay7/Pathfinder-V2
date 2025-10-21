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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('provider'); // coursera, udemy, edx, etc.
            $table->string('category'); // programming, business, design, etc.
            $table->string('level')->default('beginner'); // beginner, intermediate, advanced
            $table->string('url')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->integer('duration_hours')->nullable();
            $table->json('skills_taught')->nullable(); // Array of skills
            $table->json('prerequisites')->nullable(); // Array of prerequisites
            $table->json('mbti_compatibility')->nullable(); // MBTI types and compatibility scores
            $table->text('mbti_explanation')->nullable(); // Why this course fits certain MBTI types
            $table->decimal('rating', 3, 2)->nullable();
            $table->integer('students_count')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['category', 'level']);
            $table->index(['provider', 'is_active']);
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};