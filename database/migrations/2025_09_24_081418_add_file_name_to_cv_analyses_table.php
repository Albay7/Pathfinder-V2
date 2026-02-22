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
        if (!Schema::hasColumn('cv_analyses', 'file_name')) {
            Schema::table('cv_analyses', function (Blueprint $table) {
                $table->string('file_name')->after('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('cv_analyses', 'file_name')) {
            Schema::table('cv_analyses', function (Blueprint $table) {
                $table->dropColumn('file_name');
            });
        }
    }
};
