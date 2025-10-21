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
            $table->string('model')->nullable()->after('room_id');
            $table->string('serial_number')->nullable()->after('model');
            $table->string('firmware_version')->nullable()->after('serial_number');
            $table->integer('port')->default(554)->after('ip_rtsp');
            $table->string('resolution')->nullable()->after('port');
            $table->integer('fps')->default(30)->after('resolution');
            $table->string('recording_schedule')->nullable()->after('recording');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cctvs', function (Blueprint $table) {
            $table->dropColumn(['model', 'serial_number', 'firmware_version', 'port', 'resolution', 'fps', 'recording_schedule']);
        });
    }
};
