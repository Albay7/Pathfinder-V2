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
        Schema::create('mbti_test_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->json('responses'); // Store all question responses
            $table->integer('e_score')->default(0); // Extraversion score
            $table->integer('i_score')->default(0); // Introversion score
            $table->integer('s_score')->default(0); // Sensing score
            $table->integer('n_score')->default(0); // Intuition score
            $table->integer('t_score')->default(0); // Thinking score
            $table->integer('f_score')->default(0); // Feeling score
            $table->integer('j_score')->default(0); // Judging score
            $table->integer('p_score')->default(0); // Perceiving score
            $table->string('result_type', 4)->nullable(); // Final MBTI type
            $table->foreignId('personality_type_id')->nullable()->constrained('mbti_personality_types');
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mbti_test_sessions');
    }
};
