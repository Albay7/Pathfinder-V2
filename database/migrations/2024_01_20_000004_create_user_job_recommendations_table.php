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
        Schema::create('user_job_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_id')->constrained('career_jobs')->onDelete('cascade');
            $table->integer('compatibility_score'); // 0-100 compatibility percentage
            $table->timestamp('recommended_at');
            $table->timestamps();
            
            $table->unique(['user_id', 'job_id']);
            $table->index(['user_id', 'compatibility_score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_job_recommendations');
    }
};