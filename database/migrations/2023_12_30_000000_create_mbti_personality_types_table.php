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
        Schema::create('mbti_personality_types', function (Blueprint $table) {
            $table->id();
            $table->string('type_code', 4)->unique(); // e.g., 'INTJ', 'ENFP'
            $table->string('name'); // e.g., 'The Architect', 'The Campaigner'
            $table->text('description'); // Detailed description
            $table->text('strengths'); // Key strengths
            $table->text('weaknesses'); // Potential weaknesses
            $table->text('career_paths'); // Suitable career paths
            $table->string('temperament'); // NT, NF, SJ, SP
            $table->string('role'); // Analyst, Diplomat, Sentinel, Explorer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mbti_personality_types');
    }
};
