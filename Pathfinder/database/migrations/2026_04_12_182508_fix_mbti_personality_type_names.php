<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Fix MBTI personality type display names to match the intro page.
     * INTP: "The Thinker" → "The Logician"
     * ISFJ: "The Protector" → "The Defender"
     */
    public function up(): void
    {
        DB::table('mbti_personality_types')
            ->where('type_code', 'INTP')
            ->update(['name' => 'The Logician']);

        DB::table('mbti_personality_types')
            ->where('type_code', 'ISFJ')
            ->update(['name' => 'The Defender']);
    }

    public function down(): void
    {
        DB::table('mbti_personality_types')
            ->where('type_code', 'INTP')
            ->update(['name' => 'The Thinker']);

        DB::table('mbti_personality_types')
            ->where('type_code', 'ISFJ')
            ->update(['name' => 'The Protector']);
    }
};
