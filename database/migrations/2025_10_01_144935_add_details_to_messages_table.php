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
        Schema::table('messages', function (Blueprint $table) {
            $table->string('subject')->nullable()->after('body');
            $table->string('type')->default('message')->after('subject');
            $table->string('priority')->default('medium')->after('type');
            $table->timestamp('archived_at')->nullable()->after('read_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['subject', 'type', 'priority', 'archived_at']);
        });
    }
};
