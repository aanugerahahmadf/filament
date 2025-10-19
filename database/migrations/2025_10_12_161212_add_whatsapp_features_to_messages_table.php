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
            // WhatsApp/Messenger features
            $table->unsignedBigInteger('reply_to_message_id')->nullable()->after('archived_at');
            $table->unsignedBigInteger('forwarded_from_user_id')->nullable()->after('reply_to_message_id');
            $table->boolean('is_edited')->default(false)->after('forwarded_from_user_id');
            $table->timestamp('edited_at')->nullable()->after('is_edited');
            $table->string('message_type')->default('text')->after('edited_at'); // text, image, file, voice, etc.
            $table->string('attachment_path')->nullable()->after('message_type');
            $table->string('attachment_name')->nullable()->after('attachment_path');
            $table->bigInteger('attachment_size')->nullable()->after('attachment_name');
            $table->string('reaction')->nullable()->after('attachment_size');
            $table->boolean('is_pinned')->default(false)->after('reaction');
            $table->timestamp('pinned_at')->nullable()->after('is_pinned');
            
            // Add soft deletes
            $table->softDeletes();
            
            // Add indexes for better performance
            $table->index(['from_user_id', 'to_user_id', 'created_at']);
            $table->index(['to_user_id', 'read_at']);
            $table->index(['reply_to_message_id']);
            $table->index(['is_pinned', 'created_at']);
            
            // Add foreign key constraints
            $table->foreign('reply_to_message_id')->references('id')->on('messages')->onDelete('set null');
            $table->foreign('forwarded_from_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['reply_to_message_id']);
            $table->dropForeign(['forwarded_from_user_id']);
            
            // Drop indexes
            $table->dropIndex(['from_user_id', 'to_user_id', 'created_at']);
            $table->dropIndex(['to_user_id', 'read_at']);
            $table->dropIndex(['reply_to_message_id']);
            $table->dropIndex(['is_pinned', 'created_at']);
            
            // Drop columns
            $table->dropColumn([
                'reply_to_message_id',
                'forwarded_from_user_id',
                'is_edited',
                'edited_at',
                'message_type',
                'attachment_path',
                'attachment_name',
                'attachment_size',
                'reaction',
                'is_pinned',
                'pinned_at',
                'deleted_at'
            ]);
        });
    }
};
