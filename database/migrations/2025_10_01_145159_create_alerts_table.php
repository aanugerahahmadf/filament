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
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->string('alertable_type');
            $table->unsignedBigInteger('alertable_id');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('message');
            $table->enum('severity', ['critical', 'high', 'medium', 'low'])->default('medium');
            $table->enum('status', ['active', 'acknowledged', 'resolved', 'suppressed'])->default('active');
            $table->string('category')->nullable();
            $table->string('source')->nullable();
            $table->timestamp('triggered_at')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('suppressed_at')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['alertable_type', 'alertable_id']);
            $table->index(['severity', 'status']);
            $table->index(['category', 'source']);
            $table->index(['triggered_at', 'resolved_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
