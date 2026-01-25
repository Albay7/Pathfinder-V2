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
            // Rich profile fields
            $table->text('bio')->nullable()->after('email'); // Short bio
            $table->string('interests')->nullable()->after('bio'); // Comma-separated interests
            $table->string('skills')->nullable()->after('interests'); // Comma-separated skills
            $table->string('career_goal')->nullable()->after('skills'); // Target career/role
            $table->string('photo_path')->nullable()->after('career_goal'); // Profile photo URL or path
            $table->integer('profile_completion')->default(0)->after('photo_path'); // Percentage of profile filled
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'bio',
                'interests',
                'skills',
                'career_goal',
                'photo_path',
                'profile_completion',
            ]);
        });
    }
};
