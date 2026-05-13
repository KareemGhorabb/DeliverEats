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
        // Update 'placed' to 'pending'
        DB::table('orders')->where('status', 'placed')->update(['status' => 'pending']);
        DB::table('order_histories')->where('from_status', 'placed')->update(['from_status' => 'pending']);
        DB::table('order_histories')->where('to_status', 'placed')->update(['to_status' => 'pending']);

        // Update 'confirmed' to 'accepted'
        DB::table('orders')->where('status', 'confirmed')->update(['status' => 'accepted']);
        DB::table('order_histories')->where('from_status', 'confirmed')->update(['from_status' => 'accepted']);
        DB::table('order_histories')->where('to_status', 'confirmed')->update(['to_status' => 'accepted']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse 'pending' to 'placed'
        DB::table('orders')->where('status', 'pending')->update(['status' => 'placed']);
        DB::table('order_histories')->where('from_status', 'pending')->update(['from_status' => 'placed']);
        DB::table('order_histories')->where('to_status', 'pending')->update(['to_status' => 'placed']);

        // Reverse 'accepted' to 'confirmed'
        DB::table('orders')->where('status', 'accepted')->update(['status' => 'confirmed']);
        DB::table('order_histories')->where('from_status', 'accepted')->update(['from_status' => 'confirmed']);
        DB::table('order_histories')->where('to_status', 'accepted')->update(['to_status' => 'confirmed']);
    }
};
