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
        Schema::table('notifications', function (Blueprint $table) {
            // Check if columns already exist before adding them
            if (!Schema::hasColumn('notifications', 'user_id')) {
                // Add user_id column for custom Notification model
                $table->unsignedBigInteger('user_id')->nullable()->after('id');

                // Add index for better query performance
                $table->index('user_id');

                // Add foreign key constraint
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }

            // Ensure polymorphic columns exist
            if (!Schema::hasColumn('notifications', 'notifiable_type')) {
                $table->string('notifiable_type')->nullable();
            }

            if (!Schema::hasColumn('notifications', 'notifiable_id')) {
                $table->unsignedBigInteger('notifiable_id')->nullable();
            }

            // Add index for polymorphic relationship
            if (!Schema::hasIndex('notifications', ['notifiable_type', 'notifiable_id'])) {
                $table->index(['notifiable_type', 'notifiable_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id']);
            $table->dropColumn('user_id');

            // Only drop polymorphic columns if they were added by this migration
            // Note: In most cases, these should be kept as they're part of Laravel's notification system
            // $table->dropIndex(['notifiable_type', 'notifiable_id']);
            // $table->dropColumn(['notifiable_type', 'notifiable_id']);
        });
    }
};
