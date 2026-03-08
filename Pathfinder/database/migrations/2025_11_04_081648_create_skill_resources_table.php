<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('skill_resources', function (Blueprint $table) {
            $table->id();
            $table->string('job_category', 50)->default('it_cs');
            $table->string('skill_key', 100); // canonical key, e.g., 'python', 'windows-support'
            $table->string('skill_display_name', 150); // e.g., 'Python', 'Windows Support'
            $table->string('resource_label', 200); // display label for the link
            $table->string('url', 1024);
            $table->string('description', 500)->nullable();
            $table->string('platform', 50)->default('youtube');
            $table->string('level', 50)->nullable(); // beginner/intermediate/advanced
            $table->boolean('is_playlist')->default(false);
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();

            $table->index(['job_category', 'skill_key']);
        });

        // Use prefix length for url to avoid MySQL key length limit
        DB::statement('ALTER TABLE skill_resources ADD UNIQUE skill_resources_skill_key_url_unique (skill_key, url(191))');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_resources');
    }
};
