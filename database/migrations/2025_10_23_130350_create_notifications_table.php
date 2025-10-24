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
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                // Add index for better query performance if it doesn't exist
                $indexName = 'notifications_notifiable_type_notifiable_id_index';
                $indexExists = collect(DB::select("SHOW INDEX FROM notifications WHERE Key_name = ?", [$indexName]))->isNotEmpty();

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
