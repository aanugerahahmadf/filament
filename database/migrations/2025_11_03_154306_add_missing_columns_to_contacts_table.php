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
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['name', 'phone', 'position', 'department', 'facebook', 'linkedin', 'notes']);
        });
    }
};
