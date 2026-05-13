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
        // Update reviews table
        Schema::table('reviews', function (Blueprint $table) {
            // Drop old column if exists
            if (Schema::hasColumn('reviews', 'rating')) {
                $table->dropColumn('rating');
            }
            
            // Add new required columns if they don't exist
            if (!Schema::hasColumn('reviews', 'restaurant_rating')) {
                $table->tinyInteger('restaurant_rating')->default(5)->after('order_id');
            }
            if (!Schema::hasColumn('reviews', 'rider_rating')) {
                $table->tinyInteger('rider_rating')->default(5)->after('restaurant_rating');
            }
            if (!Schema::hasColumn('reviews', 'rider_id')) {
                $table->foreignId('rider_id')->nullable()->after('restaurant_id')->constrained('users')->onDelete('set null');
            }
            
            // Rename comment to restaurant_comment if it exists as 'comment'
            if (Schema::hasColumn('reviews', 'comment')) {
                $table->renameColumn('comment', 'restaurant_comment');
            }
        });

        // Update restaurants table
        Schema::table('restaurants', function (Blueprint $table) {
            if (!Schema::hasColumn('restaurants', 'delivery_fee')) {
                $table->decimal('delivery_fee', 8, 2)->default(25.00)->after('min_order_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['rider_id']);
            $table->dropColumn(['rider_id', 'restaurant_rating', 'rider_rating']);
            $table->renameColumn('restaurant_comment', 'comment');
            $table->tinyInteger('rating')->default(5);
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('delivery_fee');
        });
    }
};
