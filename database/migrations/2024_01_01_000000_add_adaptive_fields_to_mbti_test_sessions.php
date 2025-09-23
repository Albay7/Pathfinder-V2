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
        Schema::table('mbti_test_sessions', function (Blueprint $table) {
            $table->string('session_type')->default('traditional')->after('user_id');
            $table->json('questions_asked')->nullable()->after('responses');
            $table->json('rl_predictions')->nullable()->after('questions_asked');
            $table->json('final_result')->nullable()->after('rl_predictions');
            $table->integer('questions_used')->nullable()->after('final_result');
            $table->decimal('efficiency', 5, 4)->nullable()->after('questions_used');
            $table->decimal('confidence', 5, 4)->nullable()->after('efficiency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mbti_test_sessions', function (Blueprint $table) {
            $table->dropColumn([
                'session_type',
                'questions_asked',
                'rl_predictions',
                'final_result',
                'questions_used',
                'efficiency',
                'confidence'
            ]);
        });
    }
};