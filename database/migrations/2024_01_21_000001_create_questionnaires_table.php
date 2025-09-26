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
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('course_category'); // programming, business, design, etc.
            $table->string('target_audience')->nullable(); // beginners, professionals, etc.
            $table->integer('estimated_duration_minutes')->default(10);
            $table->json('skills_assessed')->nullable(); // Array of skills this questionnaire assesses
            $table->json('career_paths')->nullable(); // Array of career paths this relates to
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['course_category', 'is_active']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaires');
    }
};