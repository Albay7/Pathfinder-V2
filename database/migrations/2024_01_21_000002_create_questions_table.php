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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->enum('question_type', ['multiple_choice', 'scale', 'yes_no', 'text'])->default('multiple_choice');
            $table->json('options')->nullable(); // For multiple choice questions
            $table->json('scoring_weights')->nullable(); // Weights for different answer options
            $table->string('skill_category')->nullable(); // Which skill this question assesses
            $table->integer('order')->default(0);
            $table->boolean('is_required')->default(true);
            $table->text('help_text')->nullable();
            $table->timestamps();

            $table->index(['questionnaire_id', 'order']);
            $table->index('skill_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};