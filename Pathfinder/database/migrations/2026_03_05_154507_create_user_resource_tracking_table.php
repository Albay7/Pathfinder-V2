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
        Schema::create('user_resource_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('resource_type'); // youtube_playlist, article, job_platform
            $table->string('title');
            $table->string('url');
            $table->text('description')->nullable();
            $table->string('source')->nullable();
            $table->string('skill')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('status')->default('saved'); // saved, in_progress, completed
            $table->timestamp('saved_at')->useCurrent();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'url']);
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'skill']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_resource_tracking');
    }
};
