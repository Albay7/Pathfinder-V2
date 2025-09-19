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
        Schema::table('users', function (Blueprint $table) {
            $table->string('mbti_type')->nullable()->after('email_verified_at');
            $table->json('mbti_scores')->nullable()->after('mbti_type');
            $table->text('mbti_description')->nullable()->after('mbti_scores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['mbti_type', 'mbti_scores', 'mbti_description']);
        });
    }
};