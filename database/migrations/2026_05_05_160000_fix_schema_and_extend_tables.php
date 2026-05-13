<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fix: Add restaurant_id FK to menu_items table.
     * Previously, the column was referenced in the model and controller
     * but never existed in the migration.
     *
     * Also update reviews table to support rider ratings,
     * and update payouts table to support rider payouts.
     */
    public function up(): void
    {
        // ── Fix menu_items: add restaurant_id ──
        if (! Schema::hasColumn('menu_items', 'restaurant_id')) {
            Schema::table('menu_items', function (Blueprint $table) {
                $table->foreignId('restaurant_id')
                    ->nullable()
                    ->after('menu_category_id')
                    ->constrained()
                    ->onDelete('cascade');
            });
        }

        // ── Extend reviews: support separate restaurant & rider ratings ──
        Schema::table('reviews', function (Blueprint $table) {
            // Rename existing columns to be more specific
            $table->renameColumn('rating', 'restaurant_rating');
            $table->renameColumn('comment', 'restaurant_comment');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('rider_id')->nullable()->after('order_id')->constrained('users')->nullOnDelete();
            $table->tinyInteger('rider_rating')->nullable()->after('restaurant_rating');
            $table->text('rider_comment')->nullable()->after('restaurant_comment');
        });

        // ── Extend payouts: support rider payouts & commission tracking ──
        Schema::table('payouts', function (Blueprint $table) {
            $table->foreignId('rider_id')->nullable()->after('restaurant_id')->constrained('users')->nullOnDelete();
            $table->decimal('platform_commission', 10, 2)->default(0)->after('amount');
            $table->decimal('net_amount', 10, 2)->default(0)->after('platform_commission');
            $table->timestamp('period_start')->nullable()->after('paid_at');
            $table->timestamp('period_end')->nullable()->after('period_start');
        });

        // Make restaurant_id nullable on payouts (for rider-only payouts)
        Schema::table('payouts', function (Blueprint $table) {
            $table->foreignId('restaurant_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropForeign(['restaurant_id']);
            $table->dropColumn('restaurant_id');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['rider_id']);
            $table->dropColumn(['rider_id', 'rider_rating', 'rider_comment']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->renameColumn('restaurant_rating', 'rating');
            $table->renameColumn('restaurant_comment', 'comment');
        });

        Schema::table('payouts', function (Blueprint $table) {
            $table->dropForeign(['rider_id']);
            $table->dropColumn(['rider_id', 'platform_commission', 'net_amount', 'period_start', 'period_end']);
        });
    }
};
