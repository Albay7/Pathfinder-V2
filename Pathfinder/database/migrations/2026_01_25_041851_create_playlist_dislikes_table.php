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
        Schema::create('playlist_dislikes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('skill');
            $table->text('playlist_url');
            $table->string('playlist_label')->nullable();
            $table->timestamps();

            // Index for faster lookups
            $table->index(['user_id', 'skill']);
        });

        // Use prefix length for text column index
        DB::statement('ALTER TABLE playlist_dislikes ADD INDEX playlist_dislikes_playlist_url_index (playlist_url(191))');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlist_dislikes');
    }
};
