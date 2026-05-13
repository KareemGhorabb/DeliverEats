<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds OAuth provider fields and makes password nullable (social-only accounts have no password).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Make password nullable so social-login users don't need a password
            $table->string('password')->nullable()->change();

            // OAuth provider name (e.g. 'google', 'github')
            $table->string('provider')->nullable()->after('remember_token');

            // Unique user ID returned by the OAuth provider
            $table->string('provider_id')->nullable()->after('provider');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable(false)->change();
            $table->dropColumn(['provider', 'provider_id']);
        });
    }
};
