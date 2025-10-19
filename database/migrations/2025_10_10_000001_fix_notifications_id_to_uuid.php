<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('notifications')) {
            return;
        }

        // Drop primary first (if exists), then convert id to CHAR(36) for UUID
        // Handle different drivers safely
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        // Some setups may already be CHAR/VARCHAR; try-catch to be resilient
        try {
            Schema::table('notifications', function (Blueprint $table) {
                // On some engines, dropping primary key requires raw statement; attempt simple drop
                try { $table->dropPrimary('PRIMARY'); } catch (\Throwable $e) { /* ignore */ }
            });
        } catch (\Throwable $e) {
            // ignore
        }

        try {
            if ($driver === 'mysql') {
                DB::statement('ALTER TABLE notifications MODIFY id CHAR(36) NOT NULL');
            } elseif ($driver === 'pgsql') {
                DB::statement('ALTER TABLE notifications ALTER COLUMN id TYPE CHAR(36)');
            } else {
                // sqlite / others: use change() when possible
                Schema::table('notifications', function (Blueprint $table) {
                    $table->char('id', 36)->primary()->change();
                });
            }
        } catch (\Throwable $e) {
            // Fallback for drivers that support schema change
            Schema::table('notifications', function (Blueprint $table) {
                try { $table->char('id', 36)->change(); } catch (\Throwable $e) { /* ignore */ }
            });
        }

        // Ensure primary key is set back to id
        try {
            Schema::table('notifications', function (Blueprint $table) {
                $table->primary('id');
            });
        } catch (\Throwable $e) {
            // ignore if already primary
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('notifications')) {
            return;
        }

        // Revert to BIGINT UNSIGNED primary (best-effort)
        try {
            Schema::table('notifications', function (Blueprint $table) {
                try { $table->dropPrimary('PRIMARY'); } catch (\Throwable $e) { /* ignore */ }
            });
        } catch (\Throwable $e) {
            // ignore
        }

        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
        try {
            if ($driver === 'mysql') {
                DB::statement('ALTER TABLE notifications MODIFY id BIGINT UNSIGNED NOT NULL');
            } elseif ($driver === 'pgsql') {
                DB::statement('ALTER TABLE notifications ALTER COLUMN id TYPE BIGINT');
            } else {
                Schema::table('notifications', function (Blueprint $table) {
                    $table->unsignedBigInteger('id')->change();
                });
            }
        } catch (\Throwable $e) {
            // ignore
        }

        try {
            Schema::table('notifications', function (Blueprint $table) {
                $table->primary('id');
            });
        } catch (\Throwable $e) {
            // ignore
        }
    }
};
