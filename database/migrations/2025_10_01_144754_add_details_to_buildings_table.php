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
        Schema::table('buildings', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->string('address')->nullable()->after('description');
            $table->string('contact_person')->nullable()->after('address');
            $table->string('phone')->nullable()->after('contact_person');
            $table->string('email')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn(['description', 'address', 'contact_person', 'phone', 'email']);
        });
    }
};
