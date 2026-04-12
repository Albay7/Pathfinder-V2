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
        Schema::table('mbti_personality_types', function (Blueprint $table) {
            $table->text('workplace_habits')->nullable()->after('weaknesses');
            $table->text('growth_advice')->nullable()->after('workplace_habits');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mbti_personality_types', function (Blueprint $table) {
            $table->dropColumn(['workplace_habits', 'growth_advice']);
        });
    }
};
