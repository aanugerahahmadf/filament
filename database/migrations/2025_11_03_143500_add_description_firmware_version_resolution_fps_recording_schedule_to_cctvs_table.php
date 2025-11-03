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
            if (!Schema::hasColumn('cctvs', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('cctvs', 'resolution')) {
                $table->string('resolution')->nullable();
            }
            if (!Schema::hasColumn('cctvs', 'fps')) {
                $table->integer('fps')->default(30);
            }
            if (!Schema::hasColumn('cctvs', 'recording_schedule')) {
                $table->text('recording_schedule')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cctvs', function (Blueprint $table) {
            $columns = ['description', 'resolution', 'fps', 'recording_schedule'];
            $existingColumns = array_filter($columns, function ($column) {
                return Schema::hasColumn('cctvs', $column);
            });
            if (!empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }
};
