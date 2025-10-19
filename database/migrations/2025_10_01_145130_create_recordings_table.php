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
        Schema::create('recordings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cctv_id')->constrained()->cascadeOnDelete();
            $table->string('filename');
            $table->string('filepath');
            $table->unsignedBigInteger('size')->nullable(); // in bytes
            $table->unsignedInteger('duration')->nullable(); // in seconds
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->string('format')->nullable(); // mp4, avi, etc.
            $table->string('resolution')->nullable(); // 1920x1080, etc.
            $table->enum('status', ['active', 'archived', 'deleted'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['cctv_id', 'status']);
            $table->index(['started_at', 'ended_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recordings');
    }
};
