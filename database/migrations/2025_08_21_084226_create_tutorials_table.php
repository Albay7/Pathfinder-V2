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
        Schema::create('tutorials', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('skill'); // The skill this tutorial teaches
            $table->string('level')->default('beginner'); // beginner, intermediate, advanced
            $table->string('type')->default('video'); // video, article, course, documentation
            $table->string('url'); // Tutorial link
            $table->string('provider')->nullable(); // YouTube, Coursera, freeCodeCamp, etc.
            $table->integer('duration_minutes')->nullable(); // Estimated duration
            $table->decimal('rating', 3, 2)->default(0); // User rating out of 5
            $table->integer('difficulty')->default(1); // 1-5 difficulty scale
            $table->json('prerequisites')->nullable(); // Required skills/knowledge
            $table->json('tags')->nullable(); // Additional tags for filtering
            $table->boolean('is_free')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['skill', 'level']);
            $table->index(['is_active', 'is_free']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutorials');
    }
};
