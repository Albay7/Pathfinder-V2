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
        Schema::create('cv_analyses', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable(); // For anonymous users
            $table->unsignedBigInteger('user_id')->nullable(); // For authenticated users
            $table->string('file_name'); // Display name for the file
            $table->string('original_filename');
            $table->string('file_path');
            $table->string('file_type');
            $table->integer('file_size');
            $table->text('extracted_text');
            $table->json('skills_extracted'); // Array of skills with TF-IDF scores
            $table->json('skill_vector'); // Normalized skill vector for similarity calculations
            $table->json('analysis_summary'); // Summary statistics
            $table->json('job_matches')->nullable(); // Top job matches with similarity scores
            $table->decimal('processing_time', 8, 3)->nullable(); // Time taken for analysis in seconds
            $table->enum('status', ['processing', 'completed', 'failed'])->default('processing');
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'created_at']);
            $table->index(['session_id', 'created_at']);
            $table->index('status');
            
            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_analyses');
    }
};