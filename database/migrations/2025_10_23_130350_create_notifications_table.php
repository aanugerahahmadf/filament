<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the notifications table already exists
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamp('archived_at')->nullable();
                $table->timestamps();

                // Add indexes
                $table->index('user_id');

                // Add foreign key constraint
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        } else {
            // Table exists, just add the missing columns
            Schema::table('notifications', function (Blueprint $table) {
                if (!Schema::hasColumn('notifications', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('id');
                    $table->index('user_id');
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                }

                if (!Schema::hasColumn('notifications', 'archived_at')) {
                    $table->timestamp('archived_at')->nullable()->after('read_at');
                }

                // Check if morphs columns exist, if not add them
                if (!Schema::hasColumn('notifications', 'notifiable_type')) {
                    $table->string('notifiable_type')->nullable();
                }

                if (!Schema::hasColumn('notifications', 'notifiable_id')) {
                    $table->unsignedBigInteger('notifiable_id')->nullable();
                }

                // Check if index exists before adding it
                $indexExists = collect(DB::select("SHOW INDEX FROM notifications WHERE Key_name = ?", ['notifications_notifiable_type_notifiable_id_index']))->isNotEmpty();
                if (!$indexExists) {
                    $table->index(['notifiable_type', 'notifiable_id']);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
