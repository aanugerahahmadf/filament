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
        // Check if the column exists before adding it
        if (!Schema::hasColumn('users', 'has_strong_password')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('has_strong_password')->default(false)->after('password');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if the column exists before dropping it
        if (Schema::hasColumn('users', 'has_strong_password')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('has_strong_password');
            });
        }
    }
};
