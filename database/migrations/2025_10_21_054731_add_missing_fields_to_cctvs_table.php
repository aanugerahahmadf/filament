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
        Schema::table('cctvs', function (Blueprint $table) {
            if (!Schema::hasColumn('cctvs', 'model')) {
                $table->string('model')->nullable()->after('room_id');
            }
            if (!Schema::hasColumn('cctvs', 'serial_number')) {
                $table->string('serial_number')->nullable()->after('model');
            }
            if (!Schema::hasColumn('cctvs', 'firmware_version')) {
                $table->string('firmware_version')->nullable()->after('serial_number');
            }
            if (!Schema::hasColumn('cctvs', 'port')) {
                $table->integer('port')->default(554)->after('ip_rtsp');
            }
            if (!Schema::hasColumn('cctvs', 'resolution')) {
                $table->string('resolution')->nullable()->after('port');
            }
            if (!Schema::hasColumn('cctvs', 'fps')) {
                $table->integer('fps')->default(30)->after('resolution');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cctvs', function (Blueprint $table) {
            $columns = ['model', 'serial_number', 'firmware_version', 'port', 'resolution', 'fps'];
            $existingColumns = array_filter($columns, function ($column) {
                return Schema::hasColumn('cctvs', $column);
            });
            if (!empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }
};
