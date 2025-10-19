<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the column already exists
        if (! Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username')->nullable()->after('name');
            });
        }

        // Update existing users to have a username based on their email
        // Use PHP to extract username from email for SQLite compatibility
        $users = DB::table('users')->whereNull('username')->orWhere('username', '')->get();
        foreach ($users as $user) {
            $username = explode('@', $user->email)[0];
            DB::table('users')->where('id', $user->id)->update(['username' => $username]);
        }

        // Make sure the username column is not nullable and unique
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable(false)->unique()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};
