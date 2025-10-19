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
            $table->string('facebook')->nullable()->after('instagram');
            $table->string('linkedin')->nullable()->after('facebook');
            $table->string('position')->nullable()->after('linkedin');
            $table->string('department')->nullable()->after('position');
            $table->string('phone')->nullable()->after('department');
            $table->text('notes')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['facebook', 'linkedin', 'position', 'department', 'phone', 'notes']);
        });
    }
};
