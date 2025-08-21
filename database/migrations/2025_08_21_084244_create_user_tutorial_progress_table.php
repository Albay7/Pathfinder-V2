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
        Schema::create('user_tutorial_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tutorial_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['not_started', 'in_progress', 'completed', 'bookmarked'])->default('not_started');
            $table->integer('progress_percentage')->default(0); // 0-100
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('time_spent_minutes')->default(0); // Time spent on tutorial
            $table->decimal('user_rating', 3, 2)->nullable(); // User's rating of the tutorial
            $table->text('notes')->nullable(); // User's personal notes
            $table->json('bookmarks')->nullable(); // Specific timestamps or sections bookmarked
            $table->timestamps();
            
            $table->unique(['user_id', 'tutorial_id']);
            $table->index(['user_id', 'status']);
            $table->index(['tutorial_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tutorial_progress');
    }
};
