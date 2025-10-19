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
            // The read_at column already exists in the model, but let's ensure it's properly indexed
            $table->index('read_at');

            // Add columns for enhanced messaging features
            $table->timestamp('delivered_at')->nullable()->after('read_at');
            $table->timestamp('last_typing_at')->nullable()->after('delivered_at');

            // Add indexes for better query performance
            $table->index(['from_user_id', 'to_user_id']);
            $table->index(['to_user_id', 'from_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex(['read_at']);
            $table->dropColumn(['delivered_at', 'last_typing_at']);
            $table->dropIndex(['from_user_id', 'to_user_id']);
            $table->dropIndex(['to_user_id', 'from_user_id']);
        });
    }
};
